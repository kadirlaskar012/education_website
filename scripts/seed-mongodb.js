const { MongoClient } = require('mongodb');
const fs = require('fs');
const path = require('path');

const uri = process.env.MONGODB_URI || "mongodb+srv://kadirlaskar012_db_user:nplbxGMjcrm40tGc@educationwebsite.41t10fn.mongodb.net/edugov_news?retryWrites=true&w=majority&appName=Educationwebsite";

async function seed() {
  console.log("Connecting to MongoDB Atlas for migration...");
  const client = new MongoClient(uri);

  try {
    await client.connect();
    const db = client.db('edugov_news');

    // Read dump
    const dumpPath = path.join(__dirname, 'sqlite_dump.json');
    if (!fs.existsSync(dumpPath)) {
      console.error("sqlite_dump.json not found!");
      return;
    }

    const data = JSON.parse(fs.readFileSync(dumpPath, 'utf8'));

    // 1. Categories
    console.log("Seeding categories...");
    const catCol = db.collection('categories');
    await catCol.createIndex({ slug: 1 }, { unique: true });
    await catCol.createIndex({ is_nav_visible: 1, sort_order: 1 });

    for (const cat of data.categories) {
      const doc = {
        id: cat.id,
        name: cat.name,
        slug: cat.slug,
        icon: cat.icon || '📌',
        description: cat.description || '',
        meta_title: cat.meta_title || `${cat.name} - EduGov News`,
        meta_description: cat.meta_description || `Latest ${cat.name} notifications and updates.`,
        is_nav_visible: Number(cat.is_nav_visible) === 1,
        sort_order: Number(cat.sort_order) || 0,
        created_at: cat.created_at ? new Date(cat.created_at) : new Date(),
        updated_at: new Date()
      };
      await catCol.updateOne({ slug: doc.slug }, { $set: doc }, { upsert: true });
    }
    console.log(`✓ Inserted/Updated ${data.categories.length} categories.`);

    // 2. Sources (Scrapers)
    console.log("Seeding scraper sources...");
    const srcCol = db.collection('sources');
    await srcCol.createIndex({ slug: 1 }, { unique: true });
    await srcCol.createIndex({ is_active: 1 });

    for (const src of data.sources) {
      const doc = {
        id: src.id,
        name: src.name,
        slug: src.slug,
        state: src.state || 'all-india',
        authority_name: src.authority_name || src.name,
        official_portal_url: src.official_portal_url || src.feed_url,
        feed_url: src.feed_url,
        scraper_type: src.scraper_type || 'html_table',
        category_id: src.category_id || 1,
        is_active: Number(src.is_active) === 1,
        check_interval_minutes: Number(src.check_interval_minutes) || 15,
        last_scraped_at: src.last_scraped_at ? new Date(src.last_scraped_at) : null,
        created_at: src.created_at ? new Date(src.created_at) : new Date(),
        updated_at: new Date()
      };
      await srcCol.updateOne({ slug: doc.slug }, { $set: doc }, { upsert: true });
    }
    console.log(`✓ Inserted/Updated ${data.sources.length} sources.`);

    // 3. Articles (Notices)
    console.log("Seeding articles & creating indexes...");
    const artCol = db.collection('articles');
    await artCol.createIndex({ slug: 1 }, { unique: true });
    await artCol.createIndex({ status: 1, published_at: -1 });
    await artCol.createIndex({ category_id: 1, status: 1, published_at: -1 });
    await artCol.createIndex({ state: 1, status: 1 });
    await artCol.createIndex({ original_url_hash: 1 });
    await artCol.createIndex({ title: "text", excerpt: "text", content_html: "text" });

    // Category & Source mappings
    const catMap = {};
    for (const c of data.categories) {
      catMap[c.id] = c;
    }
    const srcMap = {};
    for (const s of data.sources) {
      srcMap[s.id] = s;
    }

    function inferState(art, src) {
      if (src && src.state && src.state !== 'all-india') {
        return { state: src.state, state_name: src.authority_name || src.name };
      }
      const text = `${art.title || ''} ${art.official_source_name || ''}`.toLowerCase();
      if (text.includes('rajasthan') || text.includes('rpsc')) return { state: 'rajasthan', state_name: 'Rajasthan' };
      if (text.includes('west bengal') || text.includes('wbpsc') || text.includes('wb police') || text.includes('wbcsc')) return { state: 'west-bengal', state_name: 'West Bengal' };
      if (text.includes('madhya pradesh') || text.includes('mppsc') || text.includes('mp esb')) return { state: 'madhya-pradesh', state_name: 'Madhya Pradesh' };
      if (text.includes('uttar pradesh') || text.includes('uppsc') || text.includes('upsssc')) return { state: 'uttar-pradesh', state_name: 'Uttar Pradesh' };
      if (text.includes('bihar') || text.includes('bpsc') || text.includes('bpssc')) return { state: 'bihar', state_name: 'Bihar' };
      if (text.includes('maharashtra') || text.includes('mpsc')) return { state: 'maharashtra', state_name: 'Maharashtra' };
      if (text.includes('upsc') || text.includes('ssc') || text.includes('nta') || text.includes('cbse') || text.includes('rrb') || text.includes('national scholarship') || text.includes('central')) {
        return { state: 'central-govt', state_name: 'Central Government' };
      }
      return { state: art.state || 'all-india', state_name: art.state_name || 'All India' };
    }

    let artCount = 0;
    for (const art of data.articles) {
      const cat = catMap[art.category_id] || { name: 'General', slug: 'general' };
      const src = art.source_id ? srcMap[art.source_id] : null;
      const stateInfo = inferState(art, src);
      
      const doc = {
        id: art.id,
        category_id: Number(art.category_id) || 1,
        category_name: cat.name,
        category_slug: cat.slug,
        source_id: art.source_id ? Number(art.source_id) : null,
        official_source_name: art.official_source_name || (src ? src.name : 'Official Portal'),
        state: stateInfo.state,
        state_name: stateInfo.state_name,
        title: art.title,
        slug: art.slug,
        excerpt: art.excerpt || '',
        content_html: art.content_html || '',
        original_url: art.original_url || '',
        original_url_hash: art.original_url_hash || '',
        pdf_url: art.pdf_url || '',
        status: art.status || 'published',
        content_score: Number(art.content_score) || 100,
        view_count: Number(art.view_count) || 0,
        is_breaking: Number(art.is_breaking) === 1,
        is_trending: Number(art.is_trending) === 1,
        is_top_featured: Number(art.is_top_featured) === 1,
        meta_title: art.meta_title || `${art.title} - EduGov News`,
        meta_description: art.meta_description || art.excerpt,
        schema_json: art.schema_json || '',
        faq_json: art.faq_json || '',
        tables_json: art.tables_json || '',
        links_json: art.links_json || '',
        published_at: art.published_at ? new Date(art.published_at) : new Date(),
        created_at: art.created_at ? new Date(art.created_at) : new Date(),
        updated_at: new Date()
      };

      await artCol.updateOne({ slug: doc.slug }, { $set: doc }, { upsert: true });
      artCount++;
    }
    console.log(`✓ Inserted/Updated ${artCount} notices in MongoDB Atlas.`);

    // 4. Site Settings
    const setCol = db.collection('settings');
    await setCol.createIndex({ key: 1 }, { unique: true });
    const defaultSettings = [
      { key: 'site_name', value: 'EduGov News' },
      { key: 'site_tagline', value: 'Instant & Verified Educational & Public Recruitment Portal' },
      { key: 'gemini_api_key', value: '' },
      { key: 'auto_publish', value: '1' },
      { key: 'enable_ai_enhancement', value: '1' },
      { key: 'indexnow_key', value: 'b2c6a992e10697bc5f1dcbe15e1cb2a0' }
    ];
    for (const s of defaultSettings) {
      await setCol.updateOne({ key: s.key }, { $set: { key: s.key, value: s.value, updated_at: new Date() } }, { upsert: true });
    }
    console.log(`✓ Seeded default site settings.`);

    console.log("\n=========================================");
    console.log("🎉 SUCCESS: All data migrated to MongoDB Atlas!");
    console.log("=========================================\n");

  } catch (err) {
    console.error("Migration error:", err);
  } finally {
    await client.close();
  }
}

seed();
