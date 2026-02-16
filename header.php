<?php
/**
 * Header template for WordPress/Tutor LMS
 * 
 * This file is used when Tutor calls get_header() via tutor_custom_header()
 * It renders the Flynt NavigationBurger component with full HTML document structure
 */

use Flynt\Api;
use Flynt\Utils\Asset;
use Flynt\Utils\Options;
use Timber\Timber;

// Get NavigationBurger component data
$componentData = apply_filters('Flynt/addComponentData?name=NavigationBurger', []);
$menu = $componentData['menu'] ?? (Timber::get_menu('navigation_burger') ?? Timber::get_pages_menu());
$logo = $componentData['logo'] ?? [
    'src' => get_theme_mod('custom_header_logo') ? get_theme_mod('custom_header_logo') : Asset::requireUrl('assets/images/logo.svg'),
    'alt' => get_bloginfo('name')
];

// Get options
$options = Options::get('translatable', 'NavigationBurger');
$labels = $options['labels'] ?? [
    'ariaLabel' => __('Main', 'flynt'),
    'toggleMenu' => __('Toggle Menu', 'flynt')
];
$ctaMenuItem = $options['ctaMenuItem'] ?? null;

// Get current post for background color
$context = Timber::context();
$post = $context['post'] ?? null;
$pageBackground = $post && method_exists($post, 'meta') ? $post->meta('pageBackground') : null;

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11" />
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="pageWrapper">
<flynt-component name="NavigationBurger" class="fixed top-0 left-0 w-screen z-50 flex flex-row justify-between mx-auto">
<header class="w-full">
<nav aria-label="<?php echo esc_attr($labels['ariaLabel']); ?>" class="h-auto w-full">
    <div class="w-full h-auto max-w-screen-max mx-auto boxed !py-[20px] grid grid-cols-3 lg:gap-sm align-middle items-center z-50 relative">
      <!-- Logo -->
      <a class="logo z-50 w-[150px] md:w-[250px] flex justify-start items-center" href="<?php echo esc_url(home_url('/')); ?>">
        <img class="logo-image w-full h-full" src="<?php echo esc_url($logo['src']); ?>" alt="<?php echo esc_attr($logo['alt']); ?>">
      </a>
      
      <?php if ($menu && $menu->items): ?>
        <div class="flex justify-center items-center">
          <!-- Hamburger Button -->
          <button data-ref="menuButton" class="hamburger scale-75 md:scale-100" type="button" aria-expanded="false" aria-controls="navigationBurger-menu">
            <span class="visuallyHidden"><?php echo esc_html($labels['toggleMenu']); ?></span>
            <span class="hamburger-lines" aria-hidden="true">
              <span class="hamburger-lines--primary"></span>
              <span class="hamburger-lines--text"></span>
            </span>
          </button>
        </div>
      <?php endif; ?>
      
      <!-- CTA -->
      <div class="w-full flex justify-end items-center">
        <?php if ($ctaMenuItem && !empty($ctaMenuItem['url'])): ?>
          <a 
            class="w-[150px] md:w-[200px]" 
            href="<?php echo esc_url($ctaMenuItem['url']); ?>" 
            <?php if (!empty($ctaMenuItem['target'])): ?> target="<?php echo esc_attr($ctaMenuItem['target']); ?>" rel="noreferrer noopener"<?php endif; ?>>
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" width="818.81" height="114.64" viewBox="0 0 818.81 114.64"><path d="M-.23,112.6V2.04h15.73l60.84,81.92-8.16,1.63V2.04h20.18v110.56h-15.88L12.53,30.09l7.42-1.63v84.14H-.23Z" fill="#303030"/><path d="M109.74,112.6V2.04h73.46v17.81h-53.28v28.34h50.31v17.81h-50.31v28.79h53.28v17.81h-73.46Z" fill="#303030"/><path d="M217.62,112.6V19.85h-28.64V2.04h77.17v17.81h-28.2v92.75h-20.33Z" fill="#303030"/><path d="M269.86,112.6v-15.88l53.57-76.87h-50.46V2.04h76.13v15.88l-53.43,76.87h54.91v17.81h-80.73Z" fill="#303030"/><path d="M383.83,112.6L354.15,2.04h21.67l23.3,94.24h-5.64L417.82,2.04h20.92l24.19,94.24h-5.64L480.59,2.04h21.67l-29.68,110.56h-23.15l-24.49-91.71h6.53l-24.49,91.71h-23.15Z" fill="#303030"/><path d="M514.57,112.6V2.04h73.46v17.81h-53.28v28.34h50.31v17.81h-50.31v28.79h53.28v17.81h-73.46Z" fill="#303030"/><path d="M602.87,112.6V2.04h41.11c7.52,0,14.17,1.36,19.96,4.08,5.79,2.72,10.31,6.68,13.58,11.87,3.26,5.19,4.9,11.5,4.9,18.92s-1.81,14.1-5.42,19.44c-3.61,5.34-8.48,9.3-14.62,11.87l25.38,44.37h-22.85l-27.16-48.53,13.06,7.72h-27.75v40.81h-20.18ZM623.05,53.98h21.22c3.66,0,6.83-.72,9.5-2.15,2.67-1.43,4.75-3.44,6.23-6.01,1.48-2.57,2.23-5.54,2.23-8.9s-.74-6.46-2.23-8.98c-1.48-2.52-3.56-4.5-6.23-5.94-2.67-1.43-5.84-2.15-9.5-2.15h-21.22v34.13Z" fill="#303030"/><path d="M700.97,112.6V2.04h20.18v57.58l-5.49-1.93,47.04-55.65h25.38l-44.08,52.24,1.19-14.25,44.22,72.57h-23.74l-29.68-48.68-14.84,17.66v31.02h-20.18Z" fill="#303030"/></svg>
          </a>
        <?php endif; ?>
      </div>
    </div>
    
    <?php if ($menu && $menu->items): ?>
      <ul
        data-ref="menu" 
        class="menu w-full h-screen !pt-[20vh] z-40 relative" 
        style="background-color: <?php echo $pageBackground ? esc_attr($pageBackground) : 'var(--white)'; ?>;">
        <div class="w-full h-full max-w-screen-max mx-auto flex flex-col justify-start items-center lg:gap-xs p-xs md:p-sm">
          <?php foreach ($menu->items as $item): ?>
            <?php if (empty($item->children)): ?>
              <!-- Regular menu item -->
              <li class="item<?php echo $item->current ? ' current-menu-item underline underline-offset-8' : ''; ?>">
                <a class="link font-menu uppercase lg:hover:underline underline-offset-8" href="<?php echo esc_url($item->link); ?>" <?php echo $item->target == '_blank' ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                  <?php echo esc_html($item->title); ?>
                </a>
              </li>
            <?php else: ?>
              <!-- Menu item with submenu -->
              <li class="item open-submenu<?php echo $item->current ? ' current-menu-item underline underline-offset-8' : ''; ?>">
                <a class="link font-menu uppercase hover:underline underline-offset-8" href="<?php echo esc_url($item->link); ?>" <?php echo $item->target == '_blank' ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                  <?php echo esc_html($item->title); ?>
                </a>
                <ul class="submenu">
                  <?php foreach ($item->children as $subitem): ?>
                    <li class="item<?php echo $subitem->current ? ' current-menu-item underline underline-offset-8' : ''; ?>">
                      <a class="link !p-0 font-menu uppercase hover:underline underline-offset-8" href="<?php echo esc_url($subitem->link); ?>" <?php echo $subitem->target == '_blank' ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html($subitem->title); ?></a>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </li>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </ul>
    <?php endif; ?>
  </nav>
</header>
</flynt-component>
