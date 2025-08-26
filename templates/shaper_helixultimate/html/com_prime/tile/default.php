<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Prime
 * @author     OneDigital <hello@onedigital.vn>
 * @copyright  @2025 PRIME GROUP
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use Joomla\CMS\Layout\LayoutHelper;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Session\Session;
use Joomla\Utilities\ArrayHelper;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers');

// *** BẮT ĐẦU PHẦN THÊM MỚI CHO CHỨC NĂNG PHÓNG TO ẢNH ***
$doc = Factory::getDocument();

// 1. Tải thư viện Lity Lightbox (CSS và JS)
$doc->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/lity/2.4.1/lity.min.css');
$doc->addScript('https://cdnjs.cloudflare.com/ajax/libs/lity/2.4.1/lity.min.js');

// 2. Thêm CSS cho nút bấm phóng to
$style = "
.swiper-slide {
    position: relative; /* Đảm bảo slide có thể chứa nút được định vị tuyệt đối */
}
.zoom-button {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 10; /* Đảm bảo nút luôn nổi lên trên */
    background: rgba(0,0,0,0.5);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    text-decoration: none;
    transition: background-color 0.3s ease, transform 0.3s ease;
    cursor: pointer;
}
.zoom-button:hover {
    background: rgba(0,0,0,0.8);
    color: white;
    transform: scale(1.1);
}
";
$doc->addStyleDeclaration($style);
// *** KẾT THÚC PHẦN THÊM MỚI ***


$template = HelixUltimate\Framework\Platform\Helper::loadTemplateData();
$tmpl_params = $template->params;

$imageFolderPath = JPATH_BASE . '/images/product/' . $this->item->sku . '/';
$imageAnimationPath = JPATH_BASE . '/images/phoi-canh/'.$this->item->sku.'.jpg';
$imageUrlAnimation = JURI::base() . 'images/phoi-canh/'.$this->item->sku.'.jpg';	
$imageFiles = glob($imageFolderPath . '*.jpg');
$imageUrls = array_map(function ($path) {
	return str_replace(JPATH_BASE, rtrim(JURI::base(), '/'), $path);
}, $imageFiles);
if (file_exists($imageAnimationPath)) {
	$imageUrls[] = $imageUrlAnimation;
}
?>

<style>
	.product-container {
		display: grid;
		grid-template-columns: 50% 46%;
		gap: 4%;
	}
	
	.product-image-main {
		display: flex;
		align-items: center;
		justify-content: center;
		background: white;
		padding: 2rem;
		margin-bottom: 1rem;
		min-height: 536px;
	}
	
	.product-image-main img {
		box-shadow: 0 0 16px rgba(0, 0, 0, 0.08);
	}
	
	.product-heading {
		font-size: 24px;
		margin-bottom: 1.5rem;
		text-transform: uppercase;
		font-weight: 600;
	}
	
	.product-collection {
		background: #BA9664;
		color: white;
		text-transform: uppercase;
		text-align: center;
		display: inline-block;
		padding: 0.5rem 2rem;
		margin-bottom: 1rem;
		border-radius: 10px;
	}
	
	.product-attribute {
		margin-bottom: 2rem;
	}
	
	.product-attribute-row {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 1rem 2rem;
		border-bottom: 1px solid #f0f0f0;
	}
	
	.product-attribute-row:nth-of-type(odd) {
		background: white;
	}
	
	.product-attribute-label {
		font-weight: 400;
	}
	
	.product-attribute-value {
		font-weight: 300;
	}
	
	.product-attribute-value a {
		color: #161616;
		text-decoration: underline;
	}
	
	.product-attribute-group {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 1rem 3rem;
		margin-bottom: 1rem;
	}
	
	.product-attribute-group-title {
		margin-bottom: 1rem;
	}
	
	.product-attribute-group-value {
		display: flex;
		gap: 10px;
		flex-wrap: wrap;
	}
	
	.product-attribute-links {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 20px;
		margin-bottom: 2rem;
	}
	
	.product-attribute-links-btn {
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 1rem 2rem;
		color: #161616;
		border: 1px solid #D2CDC8;
		background: #BA9664;
		color: white;
	}
	
	.site .product-attribute-links-btn:hover {
		background: #AB3A3E;
		color: white;
	}
	
	.product-attribute-bg-icon {
		background: no-repeat center center;
		width: 24px;
		height: 24px;
		margin-right: 0.5rem;
		display: inline-block;
	}
	
	.product-attribute-bg-icon-video {
		background-image: url('./templates/shaper_helixultimate/images/icon-video.png');
	}
	
	.product-attribute-bg-icon-live {
		background-image: url('./templates/shaper_helixultimate/images/icon-360-live-white.png');
	}
	
	.article-ratings-social-share {
		display: flex;
		align-items: center;
	}
	
	.article-ratings-social-share-title {
		margin-right: 2rem;
	}
	
	.product-images .swiper-wrapper {
		align-items: center;
	}
	
	.product-images .swiper-wrapper img {
		margin: 0 auto;
	}
	
	.product-thumbnails-wrapper {
		position: relative;
		max-width: 458px;
		margin: 0 auto;
	}
	
	.product-thumbnails {
		max-width: 338px; 
	}
	
	.product-page .swiper-button {
		height: 49px;
		line-height: 49px;
		width: 49px;
		color: #161616;
		font-size: 16px;
		border-radius: 0px;
		border: 1px solid #D2CDC8;
		background: white no-repeat center center;
		position: absolute;
	}
	
	.product-page .swiper-button.swiper-button-disabled {
		opacity: 0.35;
		cursor: auto;
		pointer-events: none;
	}
	
	.product-page .swiper-button:after {
		display: none;
	}
	
	.product-page .swiper-button-next,
	.product-page .related-button-next	{
		background-image: url('/images/arrow-right.png');
		right: 0;
	}
	
	.product-page .swiper-button-prev,
	.product-page .related-button-prev	{
		background-image: url('/images/arrow-left.png');
		left: 0;
	}
	
	.product-thumbnails-wrapper .swiper-slide {
		cursor: pointer;
	}
	
	.swiper-slide-thumb-active {
		border: 2px solid #ab3a3e;
	}
	
	.related-products {
		position: relative;
		margin-top: 4rem;
	}
	
	.related-products-heading {
		font-size: 34px;
		font-weight: 500;
		margin-bottom: 2rem;
	}
	
	.product-page .related-button-next {
		top: 0;
		right: 0;
	}
	
	.product-page .related-button-prev {
		top: 0;
		right: 70px;
		left: auto;
	}
	
	.wrap-related-product-item {
		text-align: center;
		border: 1px solid #D9D9D9;
		padding: 10px;
		display: block;
	}
	
	.related-product-item-image {
		margin-bottom: 1rem;
		box-shadow: 0px 0px 5px #ccc;
	}
	
	.related-product-item-type {
		font-size: 12px;
		margin-bottom: 0.5rem;
		color: #161616;
	}
	
	.related-product-item-sku {
		color: #8A9298;
	}
	
	@media (max-width: 991px) {
		.product-container {
			display: block;
		}
		
		.product-images {
			margin-bottom: 1.5rem;
		}
		
		.product-image-main {
			min-height: 0;
		}
	}
	
	@media (max-width: 767px) {
		.product-thumbnails-wrapper {
			max-width: 411px;
		}
		
		.product-thumbnails {
			max-width: 251px; 
		}
	}
	
</style>

<div class="product-page">
	<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
    <?php endif;?>

	<div class="product-wrapper">
		<div class="product-container">
			<div class="product-images">
				<div class="swiper product-image-main">
					<div class="swiper-wrapper">
					  <?php
					    foreach ($imageUrls as $imageUrl) {
							echo '<div class="swiper-slide">';
							echo '<img src="' . $imageUrl . '" alt="Image">';
							// *** THÊM NÚT PHÓNG TO ***
							echo '<a href="' . $imageUrl . '" data-lity class="zoom-button" title="Phóng to ảnh"><span class="icon-search-plus" aria-hidden="true"></span></a>';
							echo '</div>';
					    }
					  ?>
					</div>
				 </div>
				 <div class="product-thumbnails-wrapper">
					 <div thumbsSlider="" class="swiper product-thumbnails">
						<div class="swiper-wrapper">
						  <?php
							foreach ($imageUrls as $imageUrl) {
								echo '<div class="swiper-slide">';
								echo '<img src="' . $imageUrl . '" alt="Image">';
								echo '</div>';
							}
						  ?>
						</div>
					</div>
					<div class="swiper-button swiper-button-next"></div>
					<div class="swiper-button swiper-button-prev"></div>
				</div>
			</div>
			<div class="product-info">
				<div class="product-heading">
					<?php echo Text::_('COM_PRIME_FORM_LBL_TILE_SKU'); ?>: <?php echo $this->item->sku; ?>
				</div>
				<div class="product-collection">
					<?php echo $this->item->collection ? $this->item->collection : Text::_('COM_PRIME_FORM_LBL_TILE_COLLECTION'); ?>
				</div>
				<div class="product-attribute">
					<div class="product-attribute-row">
						<div class="product-attribute-label">
							<?php echo Text::_('COM_PRIME_FORM_LBL_TILE_AREA'); ?>
						</div>
						<div class="product-attribute-value">
							<?php 
							$area_url = "index.php?option=com_prime&view=tiles";
							$area_url .= "&area[]=";
							$areas = array();
							foreach($this->item->area_ids AS $i=>$area_id) {								
								$area_html = '<a href="'.Route::_($area_url.$area_id).'">';
								$area_html .= $this->item->area_titles[$i].'</a>';								
								$areas[] = $area_html;
							}
							echo implode(", ", $areas);
							?>							
						</div>
					</div>
					<div class="product-attribute-row">
						<div class="product-attribute-label">
							<?php echo Text::_('COM_PRIME_FORM_LBL_TILE_DESIGN'); ?>
						</div>
						<div class="product-attribute-value">
							<?php 
							$design_url = "index.php?option=com_prime&view=tiles";
							$design_url .= "&design[]=".implode("&design[]=", $this->item->design_id);
							$design_url = Route::_($design_url);
							?>
							<a href="<?php echo $design_url; ?>"><?php echo $this->item->design; ?></a>							
						</div>
					</div>
					<div class="product-attribute-row">
						<div class="product-attribute-label">
							<?php echo Text::_('COM_PRIME_FORM_LBL_TILE_COLOR'); ?>
						</div>
						<div class="product-attribute-value">
							<?php 
							$color_url = "index.php?option=com_prime&view=tiles";
							$color_url .= "&color[]=".implode("&color[]=", $this->item->color_id);
							$color_url = Route::_($color_url);
							?>
							<a href="<?php echo $color_url; ?>"><?php echo $this->item->color; ?></a>							
						</div>
					</div>
					<div class="product-attribute-row">
						<div class="product-attribute-label">
							<?php echo Text::_('COM_PRIME_FORM_LBL_TILE_SIZE'); ?>
						</div>
						<div class="product-attribute-value">
							<?php 
							$size_url = "index.php?option=com_prime&view=tiles";
							$size_url .= "&size[]=".implode("&size[]=", $this->item->size_id);
							$size_url = Route::_($size_url);
							?>
							<a href="<?php echo $size_url; ?>"><?php echo $this->item->size; ?></a>
						</div>
					</div>
					<div class="product-attribute-row">
						<div class="product-attribute-label">
							<?php echo Text::_('COM_PRIME_FORM_LBL_TILE_TYPE'); ?>
						</div>
						<div class="product-attribute-value">
							<?php 
							$type_url = "index.php?option=com_prime&view=tiles";
							$type_url .= "&type[]=";
							$types = array();
							foreach($this->item->type_ids AS $i=>$type_id) {								
								$type_html = '<a href="'.Route::_($type_url.$type_id).'">';
								$type_html .= $this->item->type_titles[$i].'</a>';								
								$types[] = $type_html;
							}
							echo implode(", ", $types);
							?>
							
						</div>
					</div>
					<div class="product-attribute-row">
						<div class="product-attribute-label">
							<?php echo Text::_('COM_PRIME_FORM_LBL_TILE_SURFACE'); ?>
						</div>
						<div class="product-attribute-value">
							<?php 
							$surface_url = "index.php?option=com_prime&view=tiles";
							$surface_url .= "&surface[]=".implode("&surface[]=", $this->item->surface_id);
							$surface_url = Route::_($surface_url);
							?>
							<a href="<?php echo $surface_url; ?>"><?php echo $this->item->surface; ?></a>
						</div>
					</div>
					<div class="product-attribute-row">
						<div class="product-attribute-label">
							<?php echo Text::_('COM_PRIME_FORM_LBL_TILE_THICKNESS'); ?>
						</div>
						<div class="product-attribute-value">
							<?php echo $this->item->thickness; ?>
						</div>
					</div>
					<div class="product-attribute-row">
						<div class="product-attribute-label">
							<?php echo Text::_('COM_PRIME_FORM_LBL_TILE_BRAND'); ?>
						</div>
						<div class="product-attribute-value">
							<?php echo $this->item->brand; ?>
						</div>
					</div>
					<div class="product-attribute-row">
						<div class="product-attribute-label">
							<?php echo Text::_('COM_PRIME_FORM_LBL_TILE_FACETILE'); ?>
						</div>
						<div class="product-attribute-value">
							<?php echo $this->item->facetile; ?>
						</div>
					</div>
					<div class="product-attribute-row">
						<div class="product-attribute-label">
							<?php echo Text::_('COM_PRIME_FORM_LBL_TILE_VARIATION'); ?>
						</div>
						<div class="product-attribute-value">
							<?php echo $this->item->variation; ?>
						</div>
					</div>
				</div>
				<div class="product-attribute-group">
					<div class="product-attribute-effects">
						<div class="product-attribute-group-title">
							<?php echo Text::_('COM_PRIME_FORM_LBL_TILE_EFFECTS'); ?>
						</div>
						<div class="product-attribute-group-value">
							<?php
							$effects = array();
							foreach($this->item->effect_ids AS $i=>$effect_id) {
								$effect_html = '<a href="'.$this->item->effect_pages[$i].'">';
								if($this->item->effect_images[$i] <> '') {
									$effect_html .= '<img src="'.$this->item->effect_images[$i].'" /></a>';
								} else {
									$effect_html .= $this->item->effect_titles[$i].'</a>';
								}
								$effects[] = $effect_html;
							}
							echo implode($effects);
							?>
						</div>
					</div>
					<div class="product-attribute-colors">
						<div class="product-attribute-group-title">
							<?php echo Text::_('COM_PRIME_FORM_LBL_TILE_GROUTCOLOR'); ?>
						</div>
						<div class="product-attribute-group-value">
							<?php 
							//echo $this->item->groutcolor;
							$groutcolors = array();
							foreach($this->item->groutcolor_ids AS $i=>$groutcolor_id) {
								$groutcolor_html = '<a href="'.$this->item->groutcolor_pages[$i].'">';
								if($this->item->groutcolor_images[$i] <> '') {
									$groutcolor_html .= '<img src="'.$this->item->groutcolor_images[$i].'" /></a>';
								} else {
									$groutcolor_html .= $this->item->groutcolor_groutcolors[$i].'</a>';
								}
								
								$groutcolors[] = $groutcolor_html;
							}
							echo implode($groutcolors);
							?>
						</div>
					</div>
				</div>
				<div class="product-attribute-links">
					<a class="product-attribute-links-btn product-attribute-video" href="<?php echo $this->item->video; ?>">
						<span class="product-attribute-bg-icon product-attribute-bg-icon-video">&nbsp;</span>
						<span class="product-attribute-link-text"><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_VIDEO'); ?></span>
					</a>
					<a class="product-attribute-links-btn product-attribute-live" href="<?php echo $this->item->live; ?>">
						<span class="product-attribute-bg-icon product-attribute-bg-icon-live">&nbsp;</span>
						<span class="product-attribute-link-text"><?php echo Text::_('COM_PRIME_FORM_LBL_TILE_LIVE'); ?></span>
					</a>
				</div>
				<?php if (($tmpl_params->get('social_share') && !$this->print)): ?>
					<div class="article-ratings-social-share d-flex">
						<div class="article-ratings-social-share-title">
							<?php echo Text::_('SHARE'); ?>
						</div>
						<div class="social-share-block">
							<?php echo LayoutHelper::render('joomla.content.social_share', $this->item); ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	
	<?php 
	// *** BẮT ĐẦU PHẦN SỬA LỖI SẢN PHẨM LIÊN QUAN ***
	$db    = JFactory::getDBO();
	$query = $db->getQuery(true);

	// 1. Sửa câu truy vấn để lấy sản phẩm ngẫu nhiên
	$query->select('DISTINCT a.*')
		  ->from('`#__prime_tiles` AS a')
		  ->where('a.state = 1')
		  ->where('a.id != ' . (int) $this->item->id); // Loại trừ sản phẩm hiện tại

	// Lấy sản phẩm cùng kích thước (lấy theo ID kích thước đầu tiên của sản phẩm hiện tại)
	if (!empty($this->item->size_id[0])) {
		$query->where('a.size = ' . (int) $this->item->size_id[0]);
	}
	
	$query->order('RAND()'); // Sửa lại cú pháp sắp xếp ngẫu nhiên
	$query->setLimit(10);
	
	$db->setQuery($query);
	$related_rows = $db->loadObjectList();

	// 2. Xử lý để chuyển ID sang Tên cho Color và Size
	if (!empty($related_rows)) {
		$color_ids = [];
		$size_ids = [];
		foreach ($related_rows as $row) {
			if (!empty($row->color)) $color_ids[] = $row->color;
			if (!empty($row->size)) $size_ids[] = $row->size;
		}

		$color_map = [];
		$size_map = [];

		if (!empty($color_ids)) {
			$query_colors = $db->getQuery(true)
				->select('id, color')->from('#__prime_colors')
				->where('id IN (' . implode(',', array_unique($color_ids)) . ')');
			$color_map = $db->setQuery($query_colors)->loadObjectList('id');
		}

		if (!empty($size_ids)) {
			$query_sizes = $db->getQuery(true)
				->select('id, size')->from('#__prime_sizes')
				->where('id IN (' . implode(',', array_unique($size_ids)) . ')');
			$size_map = $db->setQuery($query_sizes)->loadObjectList('id');
		}

		// Gán lại tên vào danh sách sản phẩm liên quan
		foreach ($related_rows as $row) {
			if (isset($color_map[$row->color])) {
				$row->color = $color_map[$row->color]->color;
			}
			if (isset($size_map[$row->size])) {
				$row->size = $size_map[$row->size]->size;
			}
		}
	}
	// *** KẾT THÚC PHẦN SỬA LỖI ***
	?>

	<?php if(!empty($related_rows)) : ?>
	<div class="related-products">
		<div class="related-products-heading">
			<?php echo JText::_( 'Có thể bạn quan tâm' );?>
		</div>
		<div class="swiper related-products-list">
			<div class="swiper-wrapper">
				<?php foreach ($related_rows as $i => $item) : ?>
					<?php 
					$imageProductPath = JPATH_BASE . '/images/product/' . $item->sku . '/' . $item->sku . '.jpg';
					$imageUrlProduct = JURI::base() . 'images/product/' . $item->sku . '/' . $item->sku . '.jpg';
					$defaultImageUrl = JURI::base() . 'images/product/notfound.jpg';
					
					$srcImgProduct = file_exists($imageProductPath) ? $imageUrlProduct : $defaultImageUrl;
					?>
					<div class="swiper-slide related-product-item">				
						<a class="wrap-related-product-item" href="<?php echo Route::_('index.php?option=com_prime&view=tile&id='.(int) $item->id); ?>">
							<img class="related-product-item-image" src="<?php echo $srcImgProduct; ?>" />
							<div class="related-product-item-info">
								<div class="related-product-item-type">
									<span clas="related-product-item-color"><?php echo $item->color; ?></span>
									-
									<span clas="related-product-item-size"><?php echo $item->size; ?></span>
								</div>
								<div class="related-product-item-sku">
									<?php echo $this->escape($item->sku); ?>
								</div>
							</div>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="swiper-button related-button-next"></div>
		<div class="swiper-button related-button-prev"></div>
	</div>
	<?php endif; ?>
</div>


<script>
	// Initialize the thumbs slider
	const thumbsSwiper = new Swiper('.product-thumbnails', {
	  spaceBetween: 10,
	  slidesPerView: 3,
	  freeMode: true,
	  watchSlidesProgress: true,
	  navigation: {
		nextEl: ".swiper-button-next",
		prevEl: ".swiper-button-prev",
	  },
	});

	// Initialize the main slider
	const mainSwiper = new Swiper('.product-image-main', {
	  effect: 'fade',
	  thumbs: {
        swiper: thumbsSwiper,
      },
	});
	
	// Sync main slider with thumbs when using navigation buttons on thumbnails
	thumbsSwiper.on('slideChange', function () {
	  const activeIndex = thumbsSwiper.activeIndex; // Get current active index of thumbs
	  mainSwiper.slideTo(activeIndex); // Slide the main slider to the same index
	});

	// relatedSwiper slider
	const relatedSwiper = new Swiper('.related-products-list', {
	  navigation: {
		nextEl: ".related-button-next",
		prevEl: ".related-button-prev",
	  },
	  breakpoints: {
			// when window width is >= 375px
			375: {
				slidesPerView: 2,
				spaceBetween: 10,
			},
			// when window width is >= 992px
			992: {
				slidesPerView: 4,
				spaceBetween: 10,
			},
			// when window width is >= 1200px
			1200: {
				slidesPerView: 6,
				spaceBetween: 21,
			},
	  },
	});
</script>