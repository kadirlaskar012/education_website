<div class="admin-header-row">
    <div>
        <h1 style="font-size: 1.5rem; color: #0a192f; margin-bottom: 0.25rem;">Edit Article</h1>
        <p style="font-size: 0.8125rem; color: #64748b;">Update article title, status, or content</p>
    </div>
    <div>
        <a href="/admin/articles" class="admin-btn admin-btn-secondary">← Back to Articles</a>
    </div>
</div>

<?php if (!empty($message)): ?>
<div style="background-color: #f0fdf4; color: #166534; padding: 0.75rem; border-radius: 4px; font-size: 0.8125rem; margin-bottom: 1rem; border: 1px solid #bbf7d0;">
    <?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>

<div class="admin-card">
    <form action="/admin/articles/edit/<?= $article['id'] ?>" method="post">
        <div class="form-group" style="margin-bottom: 1rem;">
            <label for="title" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Article Title</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($article['title']) ?>" required style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.875rem;">
        </div>

        <div class="form-group" style="margin-bottom: 1rem;">
            <label for="status" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Publication Status</label>
            <select id="status" name="status" style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.875rem;">
                <option value="published" <?= $article['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                <option value="updated" <?= $article['status'] === 'updated' ? 'selected' : '' ?>>Updated</option>
                <option value="draft" <?= $article['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="review" <?= $article['status'] === 'review' ? 'selected' : '' ?>>Review</option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 1rem;">
            <label for="summary" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Summary</label>
            <textarea id="summary" name="summary" rows="3" style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.875rem;"><?= htmlspecialchars($article['summary']) ?></textarea>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="content_html" style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.35rem;">Content HTML (Grounded Tables, FAQs, & Step Guide)</label>
            <textarea id="content_html" name="content_html" rows="12" style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.8125rem; font-family: monospace;"><?= htmlspecialchars($article['content_html']) ?></textarea>
        </div>

        <button type="submit" class="admin-btn admin-btn-primary">
            💾 Save Changes
        </button>
    </form>
</div>
