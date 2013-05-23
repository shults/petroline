<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n" ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach ($staticPages as $sp): ?>
        <url>
            <loc><?php echo Yii::app()->request->getBaseUrl(true) . $sp ?></loc>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    <?php endforeach; ?>
    <?php foreach ($categories as $category): ?>
        <url>
            <loc><?php echo Yii::app()->request->getBaseUrl(true) . $category->getFrontUrl() ?></loc>
            <changefreq>dayly</changefreq>
            <priority>0.8</priority>
        </url>
    <?php endforeach; ?>
    <?php foreach ($products as $product): ?>
        <url>
            <loc><?php echo Yii::app()->request->getBaseUrl(true) . $product->getFrontUrl() ?></loc>
            <lastmod><?php echo $product->updated_at ?></lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    <?php endforeach; ?>
</urlset>
