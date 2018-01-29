<?php
namespace Elementor;

function eletheme_elementor_init(){
    Plugin::instance()->elements_manager->add_category(
        'eletemplator',
        [
            'title'  => 'Eletemplator',
            'icon' => 'font'
        ],
        1
    );
}
add_action('elementor/init','Elementor\eletheme_elementor_init');

class parseMenuItems {

  private $elemente=array();
  private $errors="";
  private $hasCats=array();
  private $submenus=1;

  function __construct($items) {
      $this->elemente=$items;
  }
  function __destruct() {
      if ($this->errors) print "Erori: " . $this->errors . "\n";
  }


  private function elementPos($sectionSlug,$key="#"){
    $i=0;
    foreach($this->elemente as $element){
      if ($element->attr_title==$key.$sectionSlug) return $i;
      $i++;
    }
    return false;
  }

  private function getElementID($sectionSlug){
    return $this->elemente[$this->elementPos($sectionSlug)]->ID;
  }

 private function getElementName($sectionSlug){
    return $this->elemente[$this->elementPos($sectionSlug)]->post_title;
  }

  public function isSection($element,$key="#"){
     if($element->attr_title[0]==$key) return true; else return false;
  }

  public function getSectionSlug($element,$key="#"){
    if($this->isSection($element,$key)){
        $sectionSlug=trim(str_replace($key, "", $element->attr_title));
        if (post_type_exists( $sectionSlug )) return $sectionSlug ; else return false;
    } else return false;
  }


  private function getTaxChilds($sectionSlug){
    return get_object_taxonomies($sectionSlug);  //this returns the category type
  }

function getTerms($taxonomy){
            $element = new \stdClass;
            $element->ID=0;
           if ($taxonomy != "language" && $taxonomy != "post_translations") //for polylang to ignore language and post_translations taxonomy
           list($sectionSlug)=$this->get_post_types_by_taxonomy($taxonomy);
           $this->treegetcat(0,$myparent,$parinte,$taxonomy,$element,$sectionSlug);
           return $this->elemente;
}

function getPosts($post_type){
        	$element = new \stdClass;
        	$element->ID=0;
			$this->getPostChilds($element,$post_type);
        	return $this->elemente;
}

function getAll($taxonomy){
        	$element = new \stdClass;
        	$element->ID=0;
	        $this->treegetcat(0,$myparent,$parinte,$taxonomy,$element,$sectionSlug);
	        list($post_type)=$this->get_post_types_by_taxonomy($taxonomy);
			$this->getPostChilds($element,$post_type);
        	return $this->elemente;
}

function get_post_types_by_taxonomy( $tax = 'category' ){
    $out = array();
    $post_types = get_post_types();
    foreach( $post_types as $post_type ){
        $taxonomies = get_object_taxonomies( $post_type );
        if( in_array( $tax, $taxonomies ) ){
            $out[] = $post_type;
        }
    }
    return $out;
}


function treegetcat($parent,&$myparent,&$parinte,$taxonomy,$element,$sectionSlug){
                  $args = array(
                          'taxonomy'    => $taxonomy, // Registered tax name
                          'hierarchical'  => false,
                          'hide_empty'  => 0,
                          'parent' => $parent,
                          'orderby'       =>  'term_order',
                          //'orderby'   => 'name',
                          'order'     => 'ASC',
                        );
                  $categorii=get_categories( $args );

                  if (count($categorii)>=1){
                      $this->submenus++;
                      $this->hasCats[$sectionSlug]=true;
                      $menu_order = count( $this->elemente ) + 100*$this->submenus;
                      foreach ( $categorii as $cat ) {
                        $new_item = new \stdClass;
                        $myparent=$cat->category_parent ? $parinte[$cat->category_parent] : $element->ID;//daca nu are categorie atunci il atribuim direct sectiunii
                        $new_item->ID =  100 - $menu_order;
                        $new_item->db_id = $new_item->ID;
                        $parinte[$cat->term_id]= $new_item->ID;
                        $new_item->menu_item_parent = $myparent;
                        $new_item->url = get_term_link( $cat );
                        $new_item->title = $cat->name;
                        $new_item->menu_order = $menu_order;
                        $this->elemente[] = $new_item;
                        $menu_order++;
                        $this->treegetcat($cat->term_id,$myparent,$parinte,$taxonomy,$element,$sectionSlug);

                      }
                  }
      }

  private function setCatChilds($sectionSlug){
        $element = $this->elemente[$this->elementPos($sectionSlug)];
        //now let's add tha cats
        $taxonomies = $this->getTaxChilds($sectionSlug);
        foreach ($taxonomies as $taxonomy ) {// daca sunt mai multe tipuri de categorii
           if ($taxonomy != "language" && $taxonomy != "post_translations") //for polylang to ignore language and post_translations taxonomy
           $this->treegetcat(0,$myparent,$parinte,$taxonomy,$element,$sectionSlug);
        }
   }

  private function setPostChilds($sectionSlug){//soon
    $element = $this->elemente[$this->elementPos($sectionSlug)];
    //now we get all the posts from the section
 	$this->getPostChilds($element,$sectionSlug);
  }

    private function getPostChilds($element,$sectionSlug){//soon
    //now we get all the posts from the section
    $query = new \WP_Query(array('post_type' => $sectionSlug));
    $posts = $query->get_posts();
    $menu_order = count( $posts ) + 10000*$this->submenus;
    if (count($posts)>1){
      foreach($posts as $post) {
            $new_item = new \stdClass;
/* selectam parinte */            
            $myparent=$post->post_parent ? $parinte[$post->post_parent] : $element->ID;//daca nu are post parinte atunci il atribuim direct sectiunii
/* continuam treaba */            
            $new_item->ID =  2000 - $menu_order;
            $new_item->db_id = $new_item->ID;
            $new_item->menu_item_parent = $myparent;//adaugam parinte
            $new_item->url = get_permalink( $post->ID );
            $new_item->title = $post->post_title;
            $new_item->menu_order = $menu_order;
            $this->elemente[] = $new_item;
            $menu_order++;
      }
      $this->submenus++;
    }

  }

  private function setSectionLink($sectionSlug,$key="#"){//sets the actually link of the custom post tyep
      $this->elemente[$this->elementPos($sectionSlug,$key)]->url=get_post_type_archive_link( $sectionSlug );
  }

  public function parseMenu(){

    foreach ($this->elemente as $element) {
      $sectionSlug=$this->getSectionSlug($element);
      if($sectionSlug){
        $this->setCatChilds($sectionSlug);
        $this->setSectionLink($sectionSlug);
        if (!$this->hasCats[$sectionSlug]) $this->setPostChilds($sectionSlug);
      }
      $sectionSlugNoKids=$this->getSectionSlug($element,"!");
      if($sectionSlugNoKids){
        $this->setSectionLink($sectionSlugNoKids,"!");
      }
    }
 //print_r($this->elemente);
   //now we need to refresh the menu elements after changes
  //  print_r($this->hasCats);
 return $this->elemente;

  }

}




