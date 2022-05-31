<?php

function wp_ajax_gks_get_slider(){
    if(!current_user_can('administrator')) die("Unauthorized action! ");
    if (!isset($_GET['gks_nonce'])) die("Hmm... looks like you didn't send any credentials... No CSRF for you! ");
    if (!wp_verify_nonce(sanitize_key($_GET['gks_nonce']), 'gks_nonce')) die("Hmm... looks like you sent invalid credentials... No CSRF for you! ");

    global $wpdb;
    $response = new stdClass();

    if(!isset($_GET['id'])){
        $response->status = 'error';
        $response->errormsg = 'Invalid slider identifier!';
        gksReturnAjax($response);
    }

    $sid = (int)$_GET['id'];
    $query = $wpdb->prepare("SELECT * FROM ".GKS_TABLE_SLIDERS." WHERE id = %d", $sid);
    $res = $wpdb->get_results( $query , OBJECT );

    if(count($res)){
        $slider = $res[0];

        $query = $wpdb->prepare("SELECT * FROM ".GKS_TABLE_SLIDES." WHERE sid = %d", $sid);
        $res = $wpdb->get_results( $query , OBJECT );

        $allCats = array();
        $slides = array();
        foreach ($res as $slide) {
            if(!empty($slide->categories)) {
                $slide->categories = explode(',', $slide->categories);
            } else {
                $slide->categories = array();
            }
            if (empty($slider->extoptions)) {
                $allCats = array_merge($allCats, $slide->categories);
            }
            if(!empty($slide->details)) {
                $slide->details = json_decode($slide->details, true);
            }

            $slides[$slide->id] = $slide;

            $picJson = json_decode(base64_decode($slide->cover), true);

            if (!isset($picJson['type']) || $picJson['type'] == GKSAttachmentType::PICTURE) {
                $picId = $picJson['id'];
                $picInfo = GKSHelper::getAttachementMeta($picId, "full");
                $pic = array(
                    "id" => $picId,
                    "src" => $picInfo ? $picInfo["src"] : '',
                );
                if (isset($picJson['uid'])) {
                    $pic['uid'] = $picJson['uid'];
                }
            } else { // youtube or vimeo
                $pic = $picJson;
            }
            $slide->cover = base64_encode(json_encode($pic));

            $pics = array();
            if($slide->pics && !empty($slide->pics)) {
                $exp = explode(",", $slide->pics);
                foreach ($exp as $item) {
                    $picJson = json_decode(base64_decode($item), true);
                    if (!isset($picJson['type']) || $picJson['type'] == 'pic') {
                        $picId = $picJson['id'];
                        $picInfo = GKSHelper::getAttachementMeta($picId, "medium");
                        $pic = array(
                            "id" => $picId,
                            "src" => $picInfo ? $picInfo["src"] : '',
                            "type" => !isset($picJson['type']) ? GKSAttachmentType::PICTURE : $picJson['type']
                        );
                        if (isset($picJson['uid'])) {
                            $pic['uid'] = $picJson['uid'];
                        }
                    } else { // youtube or vimeo
                        $pic = $picJson;
                    }

                    $pics[] = base64_encode(json_encode($pic));
                }
            }
            $slide->pics = implode(",", $pics);
        }

        if (empty($slider->extoptions)) {
            $allCats = array_unique($allCats);
            sort($allCats, SORT_REGULAR);
            $formattedCats = array();
            for ($i = 0; $i < count($allCats); $i++) {
                $formattedCats[$allCats[$i]] = $i;
            }
            $slider->extoptions = array('all_cats' => $formattedCats);
        } else {
            $extOptions = json_decode($slider->extoptions, true);
            $allCats = isset($extOptions['all_cats']) ? $extOptions['all_cats'] : array();
            $formattedCats = array();
            for ($i = 0; $i < count($allCats); $i++) {
                $formattedCats[$allCats[$i]['name']] = $allCats[$i]['order'];
            }
            $extOptions['all_cats'] = $formattedCats;
            $slider->extoptions = $extOptions;
        }
        $slider->slides = $slides;
        $slider->corder = explode(',',$slider->corder);
        $slider->options = json_decode( base64_decode($slider->options), true);

        $response->status = 'success';
        $response->slider = $slider;
    }else{
        $response->status = 'error';
        $response->errormsg = 'Unknown slider identifier!';
    }

    gksReturnAjax($response);
}

function wp_ajax_gks_save_slider() {
    if(!current_user_can('administrator')) die("Unauthorized action! ");
    if (!isset($_POST['gks_nonce'])) die("Hmm... looks like you didn't send any credentials... No CSRF for you! ");
    if (!wp_verify_nonce(sanitize_key($_POST['gks_nonce']), 'gks_nonce')) die("Hmm... looks like you sent invalid credentials... No CSRF for you! ");

    $newSlider = false;

    global $wpdb;
    $response = new stdClass();

    if(!isset($_POST['slider'])){
        $response->status = 'error';
        $response->errormsg = 'Invalid slider passed!';
        gksReturnAjax($response);
    }
    //Convert to stdClass object
    $slider = json_decode( stripslashes($_POST['slider']), true);
    $sid = isset($slider['id']) ? (int)$slider['id'] : 0;

    $corder = array_map('intval', $slider['corder']);
    $corder = isset($slider['corder']) ? implode(',', $corder)  : "";

    //Insert if slider is draft yet
    $isDraft = isset($slider['isDraft']) && (int)$slider['isDraft'];
    if($isDraft){
        $title = isset($slider['title']) ? filter_var($slider['title'], FILTER_SANITIZE_STRING) : "";

        $wpdb->insert(
            GKS_TABLE_SLIDERS,
            array(
                'title' => $title,
            ),
            array(
                '%s',
            )
        );

        //Get real identifier and use it instead of draft identifier for tmp usage
        $sid = $wpdb->insert_id;

        $newSlider = true;
    }

    $slides = isset($slider['slides']) ? $slider['slides'] : array();
    $catList = array();

    foreach($slides as $id => $slide){
        $cover = isset($slide['cover']) ? $slide['cover'] : "";
        if (empty($cover)) {
            continue;
        }

        if (empty(GKSHelper::validatedBase64String($cover))) {
            continue;
        }

        //NOTE: We allow users to enter custom HTML content, but not only texts, for the title, description
        $title = isset($slide['title']) ? $slide['title'] : "";
        $description = isset($slide['description']) ? $slide['description'] : "";

        $url = isset($slide['url']) ? filter_var($slide['url'], FILTER_VALIDATE_URL) : "";
        $pics = isset($slide['pics']) ? GKSHelper::validatedBase64String($slide['pics']) : "";

        $csssel = isset($slide['csssel']) ? $slide['csssel'] : "";

        //Details JSON is not supported yet
        $details = ""; //isset($slide['details']) ? $slide['details'] : '';

        $cats = "";
        /* Caretories are not supported for sliders
        $cats = isset($slide['categories']) ? implode(',',$slide['categories']) : "";
        $catList = array_merge($catList, $slide['categories']);
        */

        if(isset($slide['isDraft']) && $slide['isDraft']){
            $wpdb->insert(
                GKS_TABLE_SLIDES,
                array(
                    'title' => $title,
                    'sid' => $sid,
                    'cover' => $cover,
                    'description' => $description,
                    'url' => $url,
                    'pics' => $pics,
                    'categories' => $cats,
                    'cdate' => date('Y-m-d H:i:s'),
                    'details' => json_encode($details),
                    'csssel' => $csssel
                ),
                array(
                    '%s',
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s'
                )
            );

            $realSlideId = $wpdb->insert_id;
            $corder = str_replace($id,$realSlideId,$corder);
        }else{
            $wpdb->update(
                GKS_TABLE_SLIDES,
                array(
                    'title' => $title,
                    'cover' => $cover,
                    'description' => $description,
                    'url' => $url,
                    'pics' => $pics,
                    'categories' => $cats,
                    'details' => json_encode($details),
                    'csssel' => $csssel,
                ),
                array( 'id' => $id ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s'
                ),
                array( '%d' )
            );
        }
    }

    $deletions = isset($slider['deletions']) ? $slider['deletions'] : array();
    $deletions = array_map('intval', $deletions);
    foreach($deletions as $deletedSlideId) {
        $wpdb->delete( GKS_TABLE_SLIDES, array( 'id' => $deletedSlideId ) );
    }

    $title = isset($slider['title']) ? filter_var($slider['title'], FILTER_SANITIZE_STRING) : "";

    $catList = array_values(array_unique($catList));
    $extOptions = array(
        'all_cats' => array(),
        'type' => (isset($slider['extoptions']['type']) ? $slider['extoptions']['type'] : GKSLayoutType::SLIDER)
    );
    $layoutType = $extOptions['type']; // for log

    $newOrder = count($catList);
    foreach ($catList as $cat) {
        if (isset($slider['extoptions']['all_cats']) && isset($slider['extoptions']['all_cats'][$cat])) {
            $order = $slider['extoptions']['all_cats'][$cat];
        } else {
            $order = $newOrder;
            $newOrder++;
        }
        $extOptions['all_cats'][] = array(
            'order' => $order,
            'name' => $cat
        );
    }
    $extOptions = json_encode($extOptions);
    $wpdb->update(
        GKS_TABLE_SLIDERS,
        array(
            'title' => $title,
            'corder' => $corder,
            'extoptions' => $extOptions
        ),
        array( 'id' => $sid ),
        array(
            '%s',
            '%s',
            '%s'
        ),
        array( '%d' )
    );

    $response->status = 'success';
    $response->sid = $sid;
    gksReturnAjax($response);
}

function wp_ajax_gks_get_options(){
    if(!current_user_can('administrator')) die("Unauthorized action! ");
    if (!isset($_GET['gks_nonce'])) die("Hmm... looks like you didn't send any credentials... No CSRF for you! ");
    if (!wp_verify_nonce(sanitize_key($_GET['gks_nonce']), 'gks_nonce')) die("Hmm... looks like you sent invalid credentials... No CSRF for you! ");

    global $wpdb;
    $response = new stdClass();

    if(!isset($_GET['id'])){
        $response->status = 'error';
        $response->errormsg = 'Invalid slider identifier!';
        gksReturnAjax($response);
    }

    $sid = (int)$_GET['id'];
    $query = $wpdb->prepare("SELECT * FROM ".GKS_TABLE_SLIDERS." WHERE id = %d", $sid);
    $res = $wpdb->get_results( $query , OBJECT );

    if(count($res)){
        $slider = $res[0];

        //die($slider->options);
        if($slider->options && !empty($slider->options)){
            $response->options = $slider->options;
        }else{
            $layoutType = GKSLayoutType::SLIDER;
            if (!empty($slider->extoptions)) {
                $extoptions = json_decode($slider->extoptions);
                if (!empty($extoptions->type)) {
                    $layoutType = $extoptions->type;
                }
            }
            $response->options = GKSHelper::getDefaultOptions(0, $layoutType);
        }

        $options = json_decode(base64_decode($response->options), true);
        if (!empty($options[GKSOption::kCustomFields])) {
            $options[GKSOption::kCustomFields] = GKSHelper::sortCustomFields($options[GKSOption::kCustomFields]);
        }
        $response->options = base64_encode(json_encode($options));

        $response->status = 'success';
    } else {
        $response->status = 'error';
        $response->errormsg = 'Slider was not found!';
    }

    gksReturnAjax($response);
}

function wp_ajax_gks_save_options() {
    if(!current_user_can('administrator')) die("Unauthorized action! ");
    if (!isset($_POST['gks_nonce'])) die("Hmm... looks like you didn't send any credentials... No CSRF for you! ");
    if (!wp_verify_nonce(sanitize_key($_POST['gks_nonce']), 'gks_nonce')) die("Hmm... looks like you sent invalid credentials... No CSRF for you! ");

    global $wpdb;
    $response = new stdClass();

    if(!isset($_POST['options']) || !isset($_POST['sid'])){
        $response->status = 'error';
        $response->errormsg = 'Invalid data passed!';
        gksReturnAjax($response);
    }

    $sid = (int)$_POST['sid'];
    //We don't sanitize options, because this options comes from our settings/UI section. User can hack and post anyting he/she wants, but this will end up with not showing sliders, nothing else!
    $options = $_POST['options'];

    $wpdb->update(
        GKS_TABLE_SLIDERS,
        array(
            'options' => $options,
        ),
        array( 'id' => $sid ),
        array(
            '%s',
        ),
        array( '%d' )
    );

    if (GKS_LICENSE_TYPE == GKS_LICENSE_TYPE_RECURRING) {
        $query = $wpdb->prepare("SELECT * FROM ".GKS_TABLE_SLIDERS." WHERE id = %d", $sid);
        $res = $wpdb->get_results( $query , OBJECT );

        if(count($res)) {
            $slider = $res[0];
            $layoutType = GKSLayoutType::SLIDER;
            if (!empty($slider->extoptions)) {
                $extoptions = json_decode($slider->extoptions);
                if (!empty($extoptions->type)) {
                    $layoutType = $extoptions->type;
                }
            }
            $licenseManager = new GKSLicenseManager();
            $fakeSliderObject = new stdClass();
            $fakeSliderObject->id = $sid;
            $fakeSliderObject->options = json_decode(base64_decode($options), true);
            $licenseManager->generateCustomCSS($fakeSliderObject, ($layoutType == GKSLayoutType::SLIDER));
        }
    }

    $response->status = 'success';
    gksReturnAjax($response);
}

function wp_ajax_gks_make_from_template()
{
    if(!current_user_can('administrator')) die("Unauthorized action! ");
    if (!isset($_POST['gks_nonce'])) die("Hmm... looks like you didn't send any credentials... No CSRF for you! ");
    if (!wp_verify_nonce(sanitize_key($_POST['gks_nonce']), 'gks_nonce')) die("Hmm... looks like you sent invalid credentials... No CSRF for you! ");

    if (!empty($_POST['id'])) {
        $templateId = (int) $_POST['id'];

        $licenseManager = new GKSLicenseManager();
        $template = $licenseManager->getTemplate($templateId);

        if (!empty($template)) {
          $newId = gksMakeFromTemplate($template);
          if ($newId !== false) {
              gksReturnAjax(array('status' => 'OK', 'redirect_url' => GKSHelper::getPageUrl($template['type'], 'edit', $newId)));
          }
        }
    }
    gksReturnAjax(array('status' => 'ERROR'));
}

function gksMakeFromTemplate($template) {
    global $wpdb;

    $sampleData = json_decode($template['sample_data'], true);

    $settings = $template['settings'];
    $corder = !empty($sampleData['corder']) ? ','.$sampleData['corder'].',' : '';

    $title = isset($sampleData['title']) ? $sampleData['title'] : "";

    $wpdb->insert(
        GKS_TABLE_SLIDERS,
        array(
            'title' => $title,
            'options' => $settings,
            'extoptions' => json_encode($sampleData['extoptions']),
            'css' => ''
        ),
        array(
            '%s',
            '%s',
            '%s',
            '%s',
        )
    );

    $sid = $wpdb->insert_id;

    //Adopt custom styles and scripts
    $decodedSettings = json_decode(base64_decode($settings), true);
    $customCSS = $decodedSettings[GKSOption::kCustomCSS];
    $customJS = $decodedSettings[GKSOption::kCustomJS];

    if (!empty($customCSS) || !empty($customJS)) {
        if (!empty($customCSS)) {
          $decodedSettings[GKSOption::kCustomCSS] = str_replace("{{{id}}}", $sid, $customCSS);
        }

        if (!empty($customJS)) {
        }

        $settings = base64_encode(json_encode($decodedSettings));

        $wpdb->update(
            GKS_TABLE_SLIDERS,
            array(
                'options' => $settings,
            ),
            array( 'id' => $sid ),
            array(
                '%s',
            ),
            array( '%d' )
        );
    }

    $slides = isset($sampleData['slides']) ? $sampleData['slides'] : array();
    $picId = GKSHelper::getSampleImage();
    $picInfo = GKSHelper::getAttachementMeta($picId, "medium");

    foreach($slides as $slide) {

        $id = $slide['id'];
        $cover = !empty($slide['cover']) ? $slide['cover'] : "";
        if (empty($cover)) {
            continue;
        } else {
            if ($cover['type'] == GKSAttachmentType::PICTURE) {
                $coverSrc = $cover['src'];

                $mediaId = GKSHelper::saveMediaImage($coverSrc);
                $picInfo = GKSHelper::getAttachementMeta($mediaId, "medium");

                $cover['id'] = $mediaId;
                $cover['src'] = $picInfo ? $picInfo['src'] : '';

                // $cover['id'] = $picId;
                // $cover['src'] = $picInfo ? $picInfo['src'] : '';
            } else {
                if (isset($cover['thumb']) && $cover['thumb']['type'] == 'custom') {
                    $thumbSrc = $cover['thumb']['src']['src'];

                    $mediaId = GKSHelper::saveMediaImage($thumbSrc);
                    $picInfo = GKSHelper::getAttachementMeta($mediaId, "medium");

                    $cover['thumb']['src'] = array(
                        'id' => $mediaId,
                        'src' => ($picInfo ? $picInfo['src'] : '')
                    );
                }
            }

            $cover = base64_encode(json_encode($cover));
        }

        $pics = array();

        $title = isset($slide['title']) ? $slide['title'] : '';
        $description = isset($slide['description']) ? $slide['description'] : '';
        $url = isset($slide['url']) ? $slide['url'] : '';
        $cats = isset($slide['categories']) ? $slide['categories'] : '';
        $details = isset($slide['details']) ? $slide['details'] : '';
        $csssel = isset($slide['csssel']) ? $slide['csssel'] : '';

        $wpdb->insert(
            GKS_TABLE_SLIDES,
            array(
                'title' => $title,
                'sid' => $sid,
                'cover' => $cover,
                'description' => $description,
                'url' => $url,
                'pics' => $pics,
                'categories' => $cats,
                'cdate' => date('Y-m-d H:i:s'),
                'details' => json_encode($details),
                'csssel' => $csssel
            ),
            array(
                '%s',
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            )
        );

        $realSlideId = $wpdb->insert_id;
        $corder = str_replace(','.$id.',', ','.$realSlideId.',', $corder);
    }

    $layoutType = $template['type']; // for log

    $wpdb->update(
        GKS_TABLE_SLIDERS,
        array(
            'corder' => substr($corder, 1, strlen($corder) - 2)
        ),
        array( 'id' => $sid ),
        array(
            '%s'
        ),
        array( '%d' )
    );

    return $sid;
}

function wp_ajax_gks_load_tiles() {
    require_once(GKS_FRONT_VIEWS_DIR_PATH."/gks-front.php");
    require_once(GKS_FRONT_VIEWS_DIR_PATH."/components/gks-tile-inflatter.php");

    $id = (int)$_GET['sid'];
    $gks_slider = GKSHelper::getSliderWithId($id);
    list($gks_categories, $gks_slider) = gks_Front::processSlider($gks_slider);

    $tilesHtml = gksGetTilesHtml($gks_slider, $gks_categories, true);
    $html = gksPrepareTilesXHR($gks_slider, $tilesHtml);

    gksReturnAjax(array('html' => $html));
}

//Helper functions
function gksReturnAjax( $response ){
    echo  json_encode( $response );
    die();
}
