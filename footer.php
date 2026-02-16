<?php

use Flynt\Utils\Options;
use Timber\Timber;

$componentData = apply_filters('Flynt/addComponentData?name=NavigationFooter', []);
$menu = $componentData['menu'] ?? (Timber::get_menu('navigation_footer') ?? Timber::get_pages_menu());
$maxLevel = $componentData['maxLevel'] ?? 0;

$options = Options::get('translatable', 'NavigationFooter') ?: [];

$labels = $options['labels'] ?? ['ariaLabel' => __('Footer', 'flynt')];

$copyrights = isset($options['copyrights']) ? $options['copyrights'] : '';
$copyrights = is_string($copyrights) ? $copyrights : '';

$textContent = isset($options['textContent']) ? $options['textContent'] : null;
$textContent = is_string($textContent) && !empty($textContent) ? $textContent : null;

$footerItems = isset($options['items']) ? $options['items'] : null;
$items = [];
if (is_array($footerItems) && !empty($footerItems)) {
	foreach ($footerItems as $item) {
		if (!is_array($item)) continue;
		$img = isset($item['image']) ? $item['image'] : null;
		$link = isset($item['imageLink']) ? $item['imageLink'] : null;
		if (!empty($img)) {
			$items[] = ['image' => $img, 'imageLink' => is_array($link) ? $link : null];
		}
	}
}

$partnersImage = isset($options['partnersImage']) ? $options['partnersImage'] : null;

?>
</div>
<div class="wpFooter">
	<flynt-component load:on="visible" name="NavigationFooter" class="bg-white">
		<div class="w-full mx-auto boxed !py-sm flex flex-row justify-between items-end">
			<div class="w-full grid grid-cols-1 md:grid-cols-3 gap-sm md:gap-lg">
				<div class="flex flex-col justify-start gap-sm">
					<div class="w-full h-full flex flex-col gap-sm justify-start">
						<?php if ($menu): ?>
						<nav class="navigation w-full h-full flex justify-start md:justify-center items-end" aria-label="<?php echo esc_attr($labels['ariaLabel']); ?>">
							<ul data-ref="menu" class="menu w-full h-full">
								<div class="w-full h-full mx-auto flex flex-col gap-sm justify-start">
									<?php if ($menu->items): foreach ($menu->items as $item): ?>
									<?php if (empty($item->children)): ?>
									<li class="item<?php echo $item->current ? ' current-menu-item' : ''; ?>">
										<a class="link font-menuFooter hover:underline" href="<?php echo esc_url($item->link); ?>" <?php echo $item->target == '_blank' ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html($item->title); ?></a>
									</li>
									<?php elseif (!empty($item->children)): ?>
									<li class="item open-submenu<?php echo $item->current ? ' current-menu-item' : ''; ?>">
										<div class="link font-menuFooter underline !mb-[6px]"><?php echo esc_html($item->title); ?></div>
										<ul class="submenu flex flex-row gap-xs">
											<?php foreach ($item->children as $subitem): ?>
											<li class="item hover:underline<?php echo $subitem->current ? ' current-menu-item' : ''; ?>">
												<a class="link !p-0 font-menuFooter" href="<?php echo esc_url($subitem->link); ?>" <?php echo $subitem->target == '_blank' ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html($subitem->title); ?></a>
											</li>
											<?php endforeach; ?>
										</ul>
									</li>
									<?php endif; endforeach; endif; ?>
								</div>
							</ul>
						</nav>
						<?php endif; ?>
					</div>
				</div>
				<div class="flex flex-col justify-start gap-sm">
					<?php if ($textContent): ?>
					<div class="w-full flex flex-col justify-start wysiwyg"><?php echo wp_kses_post($textContent); ?></div>
					<?php endif; ?>
					<div class="w-full flex flex-col justify-start uppercase"><?php echo wp_kses_post((string) $copyrights); ?></div>
					<div class="w-full mx-auto flex flex-row gap-sm">
						<?php
						foreach ($items as $item) {
							$img = isset($item['image']) ? $item['image'] : null;
							$link = isset($item['imageLink']) ? $item['imageLink'] : null;
							if (!$img) continue;
							$imgId = 0;
							$imgUrl = '';
							$imgAlt = '';
							$imgSrcset = '';
							if (is_object($img) && method_exists($img, 'src')) {
								$imgId = isset($img->ID) ? (int) $img->ID : 0;
								$imgUrl = $img->src('full');
								$imgAlt = method_exists($img, 'alt') ? $img->alt() : '';
								$imgSrcset = method_exists($img, 'srcset') ? ($img->srcset('full') ?: '') : '';
							} elseif (is_numeric($img)) {
								$imgId = (int) $img;
								$imgUrl = wp_get_attachment_image_url($imgId, 'full') ?: '';
								$imgSrcset = wp_get_attachment_image_srcset($imgId) ?: '';
							} elseif (is_array($img)) {
								$imgId = (int) (isset($img['ID']) ? $img['ID'] : (isset($img['id']) ? $img['id'] : 0));
								$imgUrl = isset($img['url']) ? $img['url'] : (isset($img['src']) ? $img['src'] : '');
								$imgAlt = isset($img['alt']) ? $img['alt'] : '';
								$imgSrcset = $imgId > 0 ? (wp_get_attachment_image_srcset($imgId) ?: '') : '';
							}
							if (empty($imgSrcset) && $imgId > 0) {
								$imgSrcset = wp_get_attachment_image_srcset($imgId) ?: '';
							}
							$linkUrl = is_array($link) ? (isset($link['url']) ? $link['url'] : '#') : '#';
							$linkTarget = is_array($link) ? (isset($link['target']) ? $link['target'] : '') : '';
							?>
						<div class="flex flex-col">
							<a href="<?php echo esc_url($linkUrl); ?>"<?php echo $linkTarget ? ' target="' . esc_attr($linkTarget) . '" rel="noreferrer noopener"' : ''; ?>>
								<figure class="figure w-full">
									<img class="lazyload w-full h-[80px] mx-auto object-contain" src="<?php echo esc_url($imgUrl); ?>" data-srcset="<?php echo esc_attr($imgSrcset); ?>" data-sizes="auto" alt="<?php echo esc_attr($imgAlt); ?>">
								</figure>
							</a>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="flex flex-col justify-between gap-sm">
					<?php
					$pId = 0;
					$pUrl = '';
					$pAlt = '';
					$pSrcset = '';
					if (!empty($partnersImage)) {
						if (is_object($partnersImage) && method_exists($partnersImage, 'src')) {
							$pId = isset($partnersImage->ID) ? (int) $partnersImage->ID : 0;
							$pUrl = $partnersImage->src('full');
							$pAlt = method_exists($partnersImage, 'alt') ? $partnersImage->alt() : '';
							$pSrcset = method_exists($partnersImage, 'srcset') ? ($partnersImage->srcset('full') ?: '') : '';
						} elseif (is_numeric($partnersImage)) {
							$pId = (int) $partnersImage;
							$pUrl = wp_get_attachment_image_url($pId, 'full') ?: '';
							$pSrcset = wp_get_attachment_image_srcset($pId) ?: '';
						} elseif (is_array($partnersImage)) {
							$pId = (int) (isset($partnersImage['ID']) ? $partnersImage['ID'] : (isset($partnersImage['id']) ? $partnersImage['id'] : 0));
							$pUrl = isset($partnersImage['url']) ? $partnersImage['url'] : (isset($partnersImage['src']) ? $partnersImage['src'] : '');
							$pAlt = isset($partnersImage['alt']) ? $partnersImage['alt'] : '';
							if (!$pUrl && $pId) $pUrl = wp_get_attachment_image_url($pId, 'full') ?: '';
							if ($pId > 0) $pSrcset = wp_get_attachment_image_srcset($pId) ?: '';
						}
					}
					if ($pId > 0 || $pUrl !== ''):
					?>
					<figure class="figure w-full">
						<img class="lazyload w-full mx-auto object-contain" src="<?php echo esc_url($pUrl); ?>" data-srcset="<?php echo esc_attr($pSrcset); ?>" data-sizes="auto" alt="<?php echo esc_attr($pAlt); ?>">
					</figure>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</flynt-component>
</div>
<?php wp_footer(); ?>
<?php if (function_exists('wp_body_close')) wp_body_close(); ?>
</body>
</html>
