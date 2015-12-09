<style>
.section{
    margin-left: -20px;
    margin-right: -20px;
    font-family: "Raleway",san-serif;
}
.section h1{
    text-align: center;
    text-transform: uppercase;
    color: #808a97;
    font-size: 35px;
    font-weight: 700;
    line-height: normal;
    display: inline-block;
    width: 100%;
    margin: 50px 0 0;
}
.section ul{
    list-style-type: disc;
    padding-left: 15px;
}
.section:nth-child(even){
    background-color: #fff;
}
.section:nth-child(odd){
    background-color: #f1f1f1;
}
.section .section-title img{
    display: table-cell;
    vertical-align: middle;
    width: auto;
    margin-right: 15px;
}
.section h2,
.section h3 {
    display: inline-block;
    vertical-align: middle;
    padding: 0;
    font-size: 24px;
    font-weight: 700;
    color: #808a97;
    text-transform: uppercase;
}

.section .section-title h2{
    display: table-cell;
    vertical-align: middle;
    line-height: 25px;
}

.section-title{
    display: table;
}

.section h3 {
    font-size: 14px;
    line-height: 28px;
    margin-bottom: 0;
    display: block;
}

.section p{
    font-size: 13px;
    margin: 25px 0;
}
.section ul li{
    margin-bottom: 4px;
}
.landing-container{
    max-width: 750px;
    margin-left: auto;
    margin-right: auto;
    padding: 50px 0 30px;
}
.landing-container:after{
    display: block;
    clear: both;
    content: '';
}
.landing-container .col-1,
.landing-container .col-2{
    float: left;
    box-sizing: border-box;
    padding: 0 15px;
}
.landing-container .col-1 img{
    width: 100%;
}
.landing-container .col-1{
    width: 55%;
}
.landing-container .col-2{
    width: 45%;
}
.premium-cta{
    background-color: #808a97;
    color: #fff;
    border-radius: 6px;
    padding: 20px 15px;
}
.premium-cta:after{
    content: '';
    display: block;
    clear: both;
}
.premium-cta p{
    margin: 7px 0;
    font-size: 14px;
    font-weight: 500;
    display: inline-block;
    width: 60%;
}
.premium-cta a.button{
    border-radius: 6px;
    height: 60px;
    float: right;
    background: url(<?php echo YITH_YWGC_ASSETS_URL?>/images/upgrade.png) #ff643f no-repeat 13px 13px;
    border-color: #ff643f;
    box-shadow: none;
    outline: none;
    color: #fff;
    position: relative;
    padding: 9px 50px 9px 70px;
}
.premium-cta a.button:hover,
.premium-cta a.button:active,
.premium-cta a.button:focus{
    color: #fff;
    background: url(<?php echo YITH_YWGC_ASSETS_URL?>/images/upgrade.png) #971d00 no-repeat 13px 13px;
    border-color: #971d00;
    box-shadow: none;
    outline: none;
}
.premium-cta a.button:focus{
    top: 1px;
}
.premium-cta a.button span{
    line-height: 13px;
}
.premium-cta a.button .highlight{
    display: block;
    font-size: 20px;
    font-weight: 700;
    line-height: 20px;
}
.premium-cta .highlight{
    text-transform: uppercase;
    background: none;
    font-weight: 800;
    color: #fff;
}

.section.one{
    background: url(<?php echo YITH_YWGC_ASSETS_URL ?>/images/01-bg.png) no-repeat #fff; background-position: 85% 75%
}
.section.two{
    background: url(<?php echo YITH_YWGC_ASSETS_URL ?>/images/02-bg.png) no-repeat #fff; background-position: 85% 75%
}
.section.three{
    background: url(<?php echo YITH_YWGC_ASSETS_URL ?>/images/03-bg.png) no-repeat #fff; background-position: 85% 75%
}
.section.four{
    background: url(<?php echo YITH_YWGC_ASSETS_URL ?>/images/04-bg.png) no-repeat #fff; background-position: 85% 75%
}
.section.five{
    background: url(<?php echo YITH_YWGC_ASSETS_URL ?>/images/05-bg.png) no-repeat #fff; background-position: 85% 75%
}
.section.six{
    background: url(<?php echo YITH_YWGC_ASSETS_URL ?>/images/06-bg.png) no-repeat #fff; background-position: 85% 75%
}
.section.seven{
    background: url(<?php echo YITH_YWGC_ASSETS_URL ?>/images/07-bg.png) no-repeat #fff; background-position: 85% 75%
}


@media (max-width: 768px) {
    .section{margin: 0}
    .premium-cta p{
        width: 100%;
    }
    .premium-cta{
        text-align: center;
    }
    .premium-cta a.button{
        float: none;
    }
}

@media (max-width: 480px){
    .wrap{
        margin-right: 0;
    }
    .section{
        margin: 0;
    }
    .landing-container .col-1,
    .landing-container .col-2{
        width: 100%;
        padding: 0 15px;
    }
    .section-odd .col-1 {
        float: left;
        margin-right: -100%;
    }
    .section-odd .col-2 {
        float: right;
        margin-top: 65%;
    }
}

@media (max-width: 320px){
    .premium-cta a.button{
        padding: 9px 20px 9px 70px;
    }

    .section .section-title img{
        display: none;
    }
}
</style>
<div class="landing">
    <div class="section section-cta section-odd">
        <div class="landing-container">
            <div class="premium-cta">
                <p>
                    <?php echo sprintf( __('Upgrade to %1$spremium version%2$s of %1$sYITH WooCommerce Gift Cards%2$s to benefit from all features!','yith-woocommerce-gift-cards'),'<span class="highlight">','</span>' );?>
                </p>
                <a href="<?php echo $this->get_premium_landing_uri() ?>" target="_blank" class="premium-cta-button button btn">
                    <span class="highlight"><?php _e('UPGRADE','yith-woocommerce-gift-cards');?></span>
                    <span><?php _e('to the premium version','yith-woocommerce-gift-cards');?></span>
                </a>
            </div>
        </div>
    </div>
    <div class="one section section-even clear">
        <h1><?php _e('Premium Features','yith-woocommerce-gift-cards');?></h1>
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_YWGC_ASSETS_URL ?>/images/01.png" alt="Feature 01" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_YWGC_ASSETS_URL ?>/images/01-icon.png" alt="icon 01"/>
                    <h2><?php _e('Fixed or variable amount?','yith-woocommerce-gift-cards');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('Choose the %1$samount of the gift cards%2$s you want to sell in your shop. The choice is simple and for everybody\'s needs. Set the amounts available for the gift cards, or let users decide the amount they want. Two different options for two positive outcomes.', 'yith-woocommerce-gift-cards'), '<b>', '</b>');?>
                </p>
            </div>
        </div>
    </div>
    <div class="two section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_YWGC_ASSETS_URL ?>/images/02-icon.png" alt="icon 02" />
                    <h2><?php _e(' Who will receive the gift?','yith-woocommerce-gift-cards');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('The receiver of the gift card is expressed by his/her email address. So easy! But what if you want to send the gift cards to more people? You just have to add all the %1$semail addresses%2$s et voilà, the gift will be sent.', 'yith-woocommerce-gift-cards'), '<b>', '</b>');?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_YWGC_ASSETS_URL ?>/images/02.png" alt="feature 02" />
            </div>
        </div>
    </div>
    <div class="three section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_YWGC_ASSETS_URL ?>/images/03.png" alt="Feature 03" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_YWGC_ASSETS_URL ?>/images/03-icon.png" alt="icon 03" />
                    <h2><?php _e( 'Customization of the gift cards','yith-woocommerce-gift-cards');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('Each gift card is totally customizable by who purchases it. %1$sImages%2$s and %1$stexts%2$s can be changed by users\' tastes, to make them free to shape their gift.', 'yith-woocommerce-gift-cards'), '<b>', '</b>');?>
                </p>
            </div>
        </div>
    </div>
    <div class="four section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_YWGC_ASSETS_URL ?>/images/04-icon.png" alt="icon 04" />
                    <h2><?php _e('...when will it be delivered?','yith-woocommerce-gift-cards');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('Users can decide when sending the email that contains the gift card: a vital feature to allow your customers to %1$spostpone the delivery of the gift%2$s. A completely automatic process, with no problems nor worries.', 'yith-woocommerce-gift-cards'), '<b>', '</b>');?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_YWGC_ASSETS_URL ?>/images/04.png" alt="Feature 04" />
            </div>
        </div>
    </div>
    <div class="five section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_YWGC_ASSETS_URL ?>/images/05.png" alt="Feature 05" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_YWGC_ASSETS_URL?>/images/05-icon.png" alt="icon 05" />
                    <h2><?php _e('I suggest you this product','yith-woocommerce-gift-cards');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __( 'Another new feature of the premium version! %1$sYou can give you a gift card and at the same time suggest what to purchase.%2$s Obviously, who will receive your card can purchase whatever he/she wants, but it is always a good thing to advise something.%3$s You will have an order of the amount of the selected product, and your shop will grow more and more.','yith-woocommerce-gift-cards' ),'<b>','</b>','<br>') ?>
                </p>
            </div>
        </div>
    </div>
    <div class="six section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_YWGC_ASSETS_URL ?>/images/06-icon.png" alt="icon 06" />
                    <h2><?php _e('Not just virtual...','yith-woocommerce-gift-cards');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('If you prefer to deliver the physical copy of the gift card, %1$syou are free to create a tailored product%2$s for this need. There are many people who prefer to touch their gifts rather than see them in an email.','yith-woocommerce-gift-cards'),'<b>','</b>'); ?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_YWGC_ASSETS_URL ?>/images/06.png" alt="Feature 06" />
            </div>
        </div>
    </div>
    <div class="section section-cta section-odd">
        <div class="landing-container">
            <div class="premium-cta">
                <p>
                    <?php echo sprintf( __('Upgrade to %1$spremium version%2$s of %1$sYITH WooCommerce Gift Cards%2$s to benefit from all features!','yith-woocommerce-gift-cards'),'<span class="highlight">','</span>' );?>
                </p>
                <a href="<?php echo $this->get_premium_landing_uri() ?>" target="_blank" class="premium-cta-button button btn">
                    <span class="highlight"><?php _e('UPGRADE','yith-woocommerce-gift-cards');?></span>
                    <span><?php _e('to the premium version','yith-woocommerce-gift-cards');?></span>
                </a>
            </div>
        </div>
    </div>
</div>