<nav class="navbar navbar-expand-lg navbar-dark bg-success text-white fixed-top">
  <div class="container">
    <a class="navbar-brand" href="/">
      <img src="/image/brand.png" class="px-3" alt="" height="20">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item mx-2">
          <a class="nav-link text-white" aria-current="page" href="/">ホーム</a>
        </li>
        <li class="nav-item mx-2">
          <a class="nav-link text-white" href="/record/">試合を記録</a>
        </li>
        <li class="nav-item mx-2">
          <a class="nav-link text-white" href="/search/?page=1">試合を検索</a>
        </li>
      </ul>
      <div class="d-flex">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item mx-2">
            <?php if (!empty($_SESSION['id'])) : ?>
              <a class="nav-link text-white btn border-white px-2" aria-current="page" href="/logout/">ログアウト</a>
            <?php else : ?>
              <a class="nav-link text-white btn border-white px-2" aria-current="page" href="/login/">ログイン</a>
            <?php endif; ?>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>