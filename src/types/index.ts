export interface Category {
  _id?: string;
  id: number;
  name: string;
  slug: string;
  icon?: string;
  description?: string;
  meta_title?: string;
  meta_description?: string;
  is_nav_visible?: boolean;
  sort_order?: number;
  created_at?: Date;
  updated_at?: Date;
}

export interface Source {
  _id?: string;
  id: number;
  name: string;
  slug: string;
  state: string;
  authority_name: string;
  official_portal_url: string;
  feed_url: string;
  scraper_type: string;
  category_id: number;
  is_active: boolean;
  check_interval_minutes: number;
  last_scraped_at?: Date | null;
  created_at?: Date;
  updated_at?: Date;
}

export interface Article {
  _id?: string;
  id: number;
  category_id: number;
  category_name: string;
  category_slug: string;
  source_id?: number | null;
  official_source_name?: string;
  state: string;
  state_name: string;
  title: string;
  slug: string;
  excerpt?: string;
  content_html: string;
  original_url?: string;
  original_url_hash?: string;
  pdf_url?: string;
  status: 'published' | 'draft' | 'in_review' | 'archived';
  content_score?: number;
  view_count?: number;
  is_breaking?: boolean;
  is_trending?: boolean;
  is_top_featured?: boolean;
  meta_title?: string;
  meta_description?: string;
  schema_json?: string;
  faq_json?: string;
  tables_json?: string;
  links_json?: string;
  published_at: Date | string;
  created_at?: Date | string;
  updated_at?: Date | string;
}

export interface SiteSetting {
  _id?: string;
  key: string;
  value: string;
  updated_at?: Date;
}
