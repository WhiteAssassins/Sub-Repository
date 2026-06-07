<header class="site-header">
  <div class="sr-container header-inner">
    <a class="brand-link" href="<?php echo e(site_url()); ?>">
      <span class="brand-mark"><i class="fa fa-code"></i></span>
      <span><?php echo e($title); ?></span>
    </a>

    <div class="main-nav" role="navigation" aria-label="Principal">
      <a class="<?php echo ($activePage ?? "") === "inicio" ? "is-active" : ""; ?>" href="<?php echo e(site_url("#inicio")); ?>">
        <i class="fa fa-home"></i> Inicio
      </a>
      <a href="<?php echo e(site_url("#explorar")); ?>">
        <i class="fa fa-link"></i> Explorar
      </a>
      <a href="<?php echo e(site_url("#categorias")); ?>">
        <i class="fa fa-th-large"></i> Categorias
      </a>
      <a href="<?php echo e(site_url("#solicitudes")); ?>">
        <i class="fa fa-envelope-open-o"></i> Solicitudes
      </a>
    </div>

    <div class="header-actions">
      <button class="icon-button" type="button" data-theme-toggle aria-label="Cambiar tema">
        <i class="fa fa-moon-o"></i>
      </button>
      <a class="primary-button" href="<?php echo e(site_url("add.php")); ?>">
        <i class="fa fa-upload"></i> Subir subtitulo
      </a>
    </div>
  </div>
</header>
