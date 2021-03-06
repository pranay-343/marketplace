<?php
/* Template variables */
$template = array(
    'name'              => 'MMF',
    'version'           => '',
    'author'            => 'MMF Infotech',
    'robots'            => 'noindex, nofollow',
    'title'             => $site_title,
    'description'       => 'Connect all people to One.',
    // true                     enable page preloader
    // false                    disable page preloader
    'page_preloader'    => false,
    // true                     enable main menu auto scrolling when opening a submenu
    // false                    disable main menu auto scrolling when opening a submenu
    'menu_scroll'       => true,
    // 'navbar-default'         for a light header
    // 'navbar-inverse'         for a dark header
    'header_navbar'     => 'navbar-default',
    // ''                       empty for a static layout
    // 'navbar-fixed-top'       for a top fixed header / fixed sidebars
    // 'navbar-fixed-bottom'    for a bottom fixed header / fixed sidebars
    'header'            => '',
    // ''                                               for a full main and alternative sidebar hidden by default (> 991px)
    // 'sidebar-visible-lg'                             for a full main sidebar visible by default (> 991px)
    // 'sidebar-partial'                                for a partial main sidebar which opens on mouse hover, hidden by default (> 991px)
    // 'sidebar-partial sidebar-visible-lg'             for a partial main sidebar which opens on mouse hover, visible by default (> 991px)
    // 'sidebar-mini sidebar-visible-lg-mini'           for a mini main sidebar with a flyout menu, enabled by default (> 991px + Best with static layout)
    // 'sidebar-mini sidebar-visible-lg'                for a mini main sidebar with a flyout menu, disabled by default (> 991px + Best with static layout)
    // 'sidebar-alt-visible-lg'                         for a full alternative sidebar visible by default (> 991px)
    // 'sidebar-alt-partial'                            for a partial alternative sidebar which opens on mouse hover, hidden by default (> 991px)
    // 'sidebar-alt-partial sidebar-alt-visible-lg'     for a partial alternative sidebar which opens on mouse hover, visible by default (> 991px)
    // 'sidebar-partial sidebar-alt-partial'            for both sidebars partial which open on mouse hover, hidden by default (> 991px)
    // 'sidebar-no-animations'                          add this as extra for disabling sidebar animations on large screens (> 991px) - Better performance with heavy pages!
    'sidebar'           => 'sidebar-partial sidebar-visible-lg sidebar-no-animations',
    // ''                       empty for a static footer
    // 'footer-fixed'           for a fixed footer
    'footer'            => '',
    // ''                       empty for default style
    // 'style-alt'              for an alternative main style (affects main page background as well as blocks style)
    'main_style'        => '',
    // ''                           Disable cookies (best for setting an active color theme from the next variable)
    // 'enable-cookies'             Enables cookies for remembering active color theme when changed from the sidebar links (the next color theme variable will be ignored)
    'cookies'           => '',
    // 'night', 'amethyst', 'modern', 'autumn', 'flatie', 'spring', 'fancy', 'fire', 'coral', 'lake',
    // 'forest', 'waterlily', 'emerald', 'blackberry' or '' leave empty for the Default Blue theme
    'theme'             => '',
    // ''                       for default content in header
    // 'horizontal-menu'        for a horizontal menu in header
    // This option is just used for feature demostration and you can remove it if you like. You can keep or alter header's content in page_head.php
    'header_content'    => '',
    'active_page'       => basename($_SERVER['PHP_SELF'])
);
/* Primary navigation array (the primary navigation will be created automatically based on this array, up to 3 levels deep) */
$primary_nav = array(
  
    array(
        'name'  => 'Personal Kit',
        'opt'   => '<a href="javascript:void(0)" data-toggle="tooltip" title="Quick Settings"><i class="gi gi-settings"></i></a>' .
                   '<a href="javascript:void(0)" data-toggle="tooltip" title="manage Admin section!"><i class="gi gi-lightbulb"></i></a>',
        'url'   => 'header',
    ),
    array(
        'name'  => 'Dashboard',
        'url'   => SELLER_URL.'dashboard/',
        'icon'  => 'gi gi-leaf'
    ),
    array(
        'name'  => 'Profile Interface',
        'icon'  => 'gi gi-certificate',
        'sub'   => array(
            array(
                'name'  => 'Profile Manage',
                'url'   => SELLER_URL.'myprofile/'
            ),
            array(
                'name'  => 'Password Manage',
                'url'   => SELLER_URL.'profile-password/'
            ),

        )
    ),
     array(
        'name'  => 'Shop Manage',
        'icon'  => 'fa fa-building',
         'name'  => 'Shop',
          'url'   => SELLER_URL.'myshop/'
   
    ),   
     array(
        'name'  => 'Policy',
        'icon'  => 'fa fa-certificate',
         'name'  => 'Policy ',
         'url'   => SELLER_URL.'shipping-and-return/'
   
    ),   
     array(
        'name'  => 'eCommerce',
        'opt'   => '<a href="javascript:void(0)" data-toggle="tooltip" title="Quick Settings"><i class="gi gi-settings"></i></a>' .
                   '<a href="javascript:void(0)" data-toggle="tooltip" title="Create or manage eCommerce section!"><i class="gi gi-lightbulb"></i></a>',
        'url'   => 'header',
    ),
      array(
        'name'  => 'Products Interface',
        'icon'  => 'fa fa-product-hunt',
         'name'  => 'Products Interface ',
         'url'   => SELLER_URL.'products/'
   
    ),  
     array(
        'name'  => 'Order Interface',
        'icon'  => 'fa fa-first-order',
         'name'  => 'Order Interface ',
         'url'   => SELLER_URL.'orders/'
    ),     
       array(
        'name'  => 'Upcoming Contest',
        'icon'  => 'fa fa-first-order',
         'name'  => 'Contest ',
         'url'   => SELLER_URL.'contest/product_votes_contest_list.php'
    ),  
  /*
    array(
        'name'  => 'Category Interface',
        'icon'  => 'gi gi-tags',
        'sub'   => array(
            array(
                'name'  => ' Add Category ',
                'url'   => SELLER_URL.'category/add-category/'
            ),
            array(
                'name'  => 'Category List',
                'url'   => SELLER_URL.'category/category-list/'
            )
        )
    ),
  
    array(
        'name'  => 'eCommerce',
        'icon'  => 'gi gi-shopping_cart',
        'sub'   => array(
            array(
                'name'  => 'Company Manage',
                'url'   => SELLER_URL.'eCommerce/company/'
            ),
            array(
                'name'  => 'Partner Product Manage',
                'url'   => SELLER_URL.'eCommerce/partner-product/'
            ),
            /*
            array(
                'name'  => 'Categroy Manage',
                'url'   => SELLER_URL.'eCommerce/category/'
            ),
            array(
                'name'  => 'Sub-Categroy Manage',
                'url'   => SELLER_URL.'eCommerce/sub-category/'
            ),
             * 
             *//*
            array(
                'name'  => 'Products',
                'url'   => SELLER_URL.'eCommerce/product/'
            ),
            array(
                'name'  => 'Order View',
                'url'   => SELLER_URL.'eCommerce/view-orders/'
            ),
            array(
                'name'  => 'Upload Prescription View',
                'url'   => SELLER_URL.'eCommerce/view-upload-prescription/'
            )
        )
    ) */
   /*
   array(
        'name'  => 'Content Interface',
        'icon'  => 'fa fa-skyatlas fa-fw',
        'sub'   => array(
            array(
               'name'  => 'General',
                'url'   => '#'
            ),
            array(
                'name'  => 'Responsive',
                'url'   => '#'
            ),
            array(
                'name'  => 'Datatables',
                'url'   => '#'
            )
        )
    ),
    array(
        'name'  => 'Develop Kit',
        'opt'   => '<a href="javascript:void(0)" data-toggle="tooltip" title="Quick Settings"><i class="gi gi-settings"></i></a>',
        'url'   => 'header',
    ),
    array(
        'name'  => 'Components',
        'icon'  => 'fa fa-wrench',
        'sub'   => array(
            array(
                'name'  => '3 Level Menu',
                'sub'   => array(
                    array(
                        'name'  => 'Link 1',
                        'url'   => '#'
                   ),
                   array(
                      'name'  => 'Link 2',
                       'url'   => '#'
                    )
                )
            ),
           array(
                'name'  => 'Maps',
                'url'   => '#'
            ),
            array(
                'name'  => 'Charts',
                'url'   => '#'
            ),
            array(
                'name'  => 'Gallery',
                'url'   => '#'
            ),
            array(
                'name'  => 'Carousel',
                'url'   => '#'
            ),
            array(
                'name'  => 'Calendar',
                'url'   => '#'
            ),
            array(
                'name'  => 'CSS3 Animations',
                'url'   => '#'
            ),
            array(
                'name'  => 'Syntax Highlighting',
                'url'   => '#'
            )
        )
    )
     * 
     */
);
if(!$_SESSION['DH_seller_id'])
{
if(isSet($_COOKIE))
{
	// Check if the cookie exists
     if(isSet($_COOKIE["Jauth"]))
	{
          $Jauth = $_COOKIE['Jauth'];
	 $auth_data = json_decode($Jauth, true);
         $_SESSION= $auth_data;
        }
}
}