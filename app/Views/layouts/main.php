<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($title ?? 'Stages CESI') ?></title>
  <meta name="description" content="<?= htmlspecialchars($metaDescription ?? 'Plateforme de stages') ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
  <header>...</header>
  <main>
    <?= $content ?>
  </main>
  <footer>...</footer>
</body>
</html>
