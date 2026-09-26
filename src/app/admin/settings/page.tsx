import React from 'react';
import { getSiteSettings } from '@/lib/db';
import { SettingsClient } from './SettingsClient';

export const revalidate = 0;

export default async function AdminSettingsPage() {
  const settings = await getSiteSettings();
  return <SettingsClient initialSettings={settings} />;
}
