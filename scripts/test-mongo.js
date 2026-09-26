const { MongoClient } = require('mongodb');

const uri = "mongodb+srv://kadirlaskar012_db_user:nplbxGMjcrm40tGc@educationwebsite.41t10fn.mongodb.net/edugov_news?retryWrites=true&w=majority&appName=Educationwebsite";

async function testConnection() {
  console.log("Connecting to MongoDB Atlas...");
  const client = new MongoClient(uri);
  try {
    await client.connect();
    console.log("SUCCESS: Connected to MongoDB Atlas cluster!");
    const db = client.db('edugov_news');
    const collections = await db.listCollections().toArray();
    console.log("Existing collections in edugov_news:", collections.map(c => c.name));
    
    // Quick ping
    const ping = await db.command({ ping: 1 });
    console.log("Ping response:", ping);
  } catch (err) {
    console.error("ERROR connecting to MongoDB:", err);
  } finally {
    await client.close();
  }
}

testConnection();
