<?php

    $layoutType = (!empty($_GET['type']) ? sanitize_text_field($_GET['type']) : '');
    $licenseManager = new GKSLicenseManager();
    $templates = $licenseManager->getTemplates($layoutType);
?>
<script>
    GKS_AJAX_URL = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>';
    GKS_NONCE = '<?php echo esc_attr(wp_create_nonce( 'gks_nonce' )); ?>';
</script>

<div id="gks-wrap" class="gks-wrap gks-glazzed-wrap">
    <?php  include_once(GKS_ADMIN_VIEWS_DIR_PATH."/gks-admin-modal-spinner.php"); ?>
    <?php include_once( GKS_ADMIN_VIEWS_DIR_PATH.'/gks-header-banner.php'); ?>
    <div class="gks-wrap-main">
        <div class="gks-options-header">
            <div class="gks-three-parts gks-fl">

            </div>

            <div class="gks-three-parts gks-fl gks-title-part gks-settings-title gks-templates-title"><span>TEMPLATES LIBRARY</span></div>

            <div class="gks-three-parts gks-fr">

            </div>
        </div>

        <hr />

        <?php
            $style = !empty($templates) ? 'style="display: none;"' : '';
            echo '<div id="gks-empty-templates" '.$style.'><div class="gks-empty-slide-list-alert"><h3>There are no available templates at this moment.</h3></div></div>';

            $html = '<div class="gks-template-list">';
            foreach ($templates as $template) {
                $html .=
                    '<div class="gks-template-list__item-box">' .
                        '<div class="gks-template-list__item" data-grid-type="'.$template['type'].'">' .
                        //'<div class="gks-template-list__item-cover" style="background-image: url(\'' . $template['cover'] . '\')"></div>';
                        '<img class="gks-template-list-item-img '. (GKS_PKG_TYPE == GKS_PKG_TYPE_FREE && $template['is_premium'] ? "locked" : "") .'" src="' . $template['cover'] . '"></img>';

                            if (GKS_PKG_TYPE == GKS_PKG_TYPE_FREE && $template['is_premium']) {
                $html .=      "<div class='gks-premium-badge gks-template-locked-badge'>" .
                              "LOCKED" .
                              "</div>";
                            }

                $html .=        '<div class="gks-template-list__item-info">'.
                                '<h3 class="gks-template-title">' . $template['title'] . '</h3>' .

                                // '<h3>' . $template['title'] . '</h3>' .
                                '<div class="gks-template-list__item-buttons">';
                                  if (GKS_PKG_TYPE == GKS_PKG_TYPE_FREE && $template['is_premium']) {
                $html .=             '<a target="_blank" href="' . GKS_PRO_URL .'" class="gks-template-list__item-use gks-glazzed-btn buy">BUY NOW</a>';
                                  } else {
                $html .=             '<button onclick="gksUseTemplate('.$template['id'].', this); return false;" data-id="'.$template['id'].'" class="gks-template-list__item-use gks-glazzed-btn gks-glazzed-btn-green">Import</button>';
                                  }

                $html .=          '<a href="'.$template['url'].'" target="_blank" class="gks-template-list__item-preview gks-glazzed-btn gks-glazzed-btn-dark">Preview</a>'.
                                '</div>'.
                                // '<p>' . $template['description'] . '</p>'.
                            '</div>' .
                        '</div>'.
                    '</div>';
            }
            $html .= '</div><br style="clear:both" />';
            echo $html;
        ?>
    </div>
</div>

<script>
    function gksSelectGridType(select)
    {
        if (jQuery(select).val() == '') {
            var itemsToShow = jQuery(".gks-template-list__item");
        } else {
            var itemsToShow  = jQuery(".gks-template-list__item[data-grid-type='"+jQuery(select).val()+"']");
        }
        jQuery(".gks-template-list__item").hide();
        if (itemsToShow.length == 0) {
            jQuery("#gks-empty-templates").show();
        } else {
            jQuery("#gks-empty-templates").hide();
        }
        itemsToShow.show();
    }

    var gksMakeFromTemplateFormLocked = false;

    function gksUseTemplate(id, btn)
    {
        var btn = jQuery(btn);
        if (gksMakeFromTemplateFormLocked) {
            return false;
        }
        gksMakeFromTemplateFormLocked = true;
        btn.attr('disabled', 'disabled');

        gks_showSpinner("Loading...");
        jQuery.ajax ( {
            type		:	'POST',
            data        :   {
                id: id,
                gks_nonce: GKS_NONCE,
                action :'gks_make_from_template'
            },
            url			: 	GKS_AJAX_URL,
            dataType	: 	'json',
            success		: 	function( response ) {
                gks_hideSpinner();
                btn.removeAttr('disabled');
                gksMakeFromTemplateFormLocked = false;
                if(response.status == 'OK') {
                    window.location.href = response.redirect_url;
                } else {
                    alert('Something went wrong! Could not create a grid from this template.');
                }
            },
            error:function( response ) {
                gks_hideSpinner();

                alert('Something went wrong! Could not create a grid from this template.');
                btn.removeAttr('disabled');
                gksMakeFromTemplateFormLocked = false;
            }
        } );
    }

</script>
