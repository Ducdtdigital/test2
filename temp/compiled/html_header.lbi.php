<!doctype html>
<html lang="vi">

<!--[if lt IE 9]>
  <script src="<?php echo $this->_var['option']['static_path']; ?>static/js/html5shiv.min.js"></script>
  <script src="<?php echo $this->_var['option']['static_path']; ?>static/js/respond.min.js"></script>
<![endif]-->
<head itemscope itemtype="http://schema.org/WebSite">
    <meta name="GENERATOR" content="THBVIETNAM | THB VIỆT NAM | #THBVIETNAM" />
	<meta charset="utf-8">
    <base href="<?php echo $this->_var['option']['static_path']; ?>">
	<?php if ($this->_var['auto_redirect'] && $this->_var['pname'] != 'respond' && $this->_var['message']['back_url']): ?>
        <meta http-equiv="refresh" content="3;url=<?php echo $this->_var['message']['back_url']; ?>">
    <?php endif; ?>
	<meta name="viewport" content="width=device-width">
    <?php if ($this->_var['title1']): ?>
	<title itemprop='name'><?php echo $this->_var['title1']; ?></title>
    <?php elseif ($this->_var['pname'] == 'brand'): ?>
    <title itemprop='name'><?php echo $this->_var['page_title_brand']; ?></title>
     <link rel="canonical" href="<?php echo $this->_var['canonical']; ?>" />
    <?php else: ?>
    <title itemprop='name'><?php echo $this->_var['page_title']; ?></title>
    <?php endif; ?>
    <?php if ($this->_var['desc_sort1']): ?>
    <meta name="description" content="<?php echo $this->_var['desc_sort1']; ?>"/>
    <?php else: ?>
    <meta name="description" content="<?php echo $this->_var['description']; ?>"/>
    <?php endif; ?>
    <?php if ($this->_var['keyword1']): ?>
    <meta name="keywords" content="<?php echo $this->_var['keyword1']; ?>"/>
    <?php else: ?>
    <meta name="keywords" content="<?php echo $this->_var['keywords']; ?>"/>
    <?php endif; ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="RATING" content="GENERAL" />
     <?php if ($this->_var['boloc'] != NULL): ?>
    <meta content="NOINDEX,NOFOLLOW" name="robots" />
     <?php elseif ($this->_var['pname'] == 'category' && $this->_var['nofflow_cat'] == "0"): ?>
    <meta content="NOINDEX,NOFOLLOW" name="robots" />
    <?php elseif ($this->_var['article']['nofollow']): ?>
     <meta content="NOINDEX,NOFOLLOW" name="robots" />
    <?php elseif ($this->_var['pname'] == 'goods' && $this->_var['goods']['is_on_sale'] == "0"): ?>
     <meta content="NOINDEX,NOFOLLOW" name="robots" />
    <?php else: ?>
    <meta content="INDEX,FOLLOW" name="robots" />
    <?php endif; ?>
    <meta name="copyright" content="<?php echo $this->_var['shop_name']; ?>" />
    <meta name="author" content="<?php echo $this->_var['shop_name']; ?>" />
    <meta http-equiv="audience" content="General" />
    <meta name="resource-type" content="Document" />
    <meta name="distribution" content="Global" />
    <meta name="revisit-after" content="1 days" />
    <meta name="GENERATOR" content="<?php echo $this->_var['shop_name']; ?>" />
    <?php if ($this->_var['pname'] == 'goods'): ?>
        <?php if ($this->_var['goods_cano'] == null): ?>
    <link rel="canonical" href="<?php echo $this->_var['canonical']; ?>" />
	    <meta itemprop="image" content="<?php echo $this->_var['option']['cdn1_path']; ?><?php echo $this->_var['goods']['goods_thumb']; ?>" />
    <meta property="og:image" content="<?php echo $this->_var['option']['cdn1_path']; ?><?php echo $this->_var['goods']['goods_thumb']; ?>" />
    <?php else: ?>
    <link rel="canonical" href="<?php echo $this->_var['goods_cano']; ?>" />
	    <meta itemprop="image" content="<?php echo $this->_var['option']['cdn1_path']; ?><?php echo $this->_var['goods']['goods_thumb']; ?>" />
    <meta property="og:image" content="<?php echo $this->_var['option']['cdn1_path']; ?><?php echo $this->_var['goods']['goods_thumb']; ?>" />
        <?php endif; ?>
    <?php endif; ?>
    <?php if ($this->_var['pname'] == 'article'): ?>
        <meta itemprop="image" content="<?php echo $this->_var['option']['cdn1_path']; ?><?php echo $this->_var['article']['article_thumb']; ?>" />
        <meta property="og:image" content="<?php echo $this->_var['option']['cdn1_path']; ?><?php echo $this->_var['article']['article_thumb']; ?>" />
    <?php endif; ?>
    <meta property="og:url" itemprop="url" content="<?php echo $this->_var['canonical']; ?>" />
    <meta property="og:title" content="<?php echo $this->_var['page_title']; ?>" />
    <meta property="og:description" content="<?php echo $this->_var['description']; ?>" />
 <?php if ($this->_var['pname'] == 'article'): ?>
<?php if ($this->_var['article']['ar_canonical'] == null): ?>
    <link rel="canonical" href="<?php echo $this->_var['canonical']; ?>" />
<?php else: ?>
    <link rel="canonical" href="<?php echo $this->_var['article']['ar_canonical']; ?>" />
<?php endif; ?>
<?php endif; ?>
    <meta property="og:site_name" content="<?php echo $this->_var['shop_name']; ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:locale" content="vi_VN" />
    <meta property="fb:app_id" content="<?php echo $this->_var['fb_app_id']; ?>" />
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->_var['option']['cdn_path']; ?>files/profile/favicon.png">
   <script type="application/ld+json">
        {
          "@context": "http://schema.org",
          "@type": "Organization",
          "url": "<?php echo $this->_var['option']['static_path']; ?>",
          "logo": "<?php echo $this->_var['option']['static_path']; ?>static/img/logo.png",
          "contactPoint": [{
            "@type": "ContactPoint",
            "telephone": "+84 <?php echo $this->_var['service_phone']; ?>",
            "contactType": "Customer service"
          }]
        }
    </script>
    <?php echo $this->_var['goods_schema']; ?>
    <?php echo $this->_var['stats_code']; ?>
    <?php echo $this->_var['render']['after_html_header']; ?>


 
