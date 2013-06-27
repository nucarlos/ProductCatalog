<?php
namespace Davzie\ProductCatalog\Controllers;
use Illuminate\Support\MessageBag;
use View;
use Redirect;
use Validator;
use Input;
use Davzie\ProductCatalog\Models\Interfaces\CategoryRepository;
use Davzie\ProductCatalog\Entities\CategoryNew;
use Davzie\ProductCatalog\Entities\CategoryEdit;

class CategoriesController extends ManageBaseController {

    /**
     * The categories object
     * @var CategoryRepository
     */
    protected $categories;

    /**
     * Let's whitelist all the methods we want to allow guests to visit!
     *
     * @access   protected
     * @var      array
     */
    protected $whitelist = [];

    /**
     * Construct shit
     */
    public function __construct( CategoryRepository $categories ){
        $this->categories = $categories;
        parent::__construct();
    }

    /**
     * Main users page.
     *
     * @access   public
     * @return   View
     */
    public function getIndex()
    {
        return View::make( 'ProductCatalog::categories.dashboard' )
                ->with( 'categories' , $this->categories->getTopLevel() );
    }

    /**
     * Edit the category
     * @param       string  $sku    The Slug Of The Category To Edit
     * @access      public
     * @return      View
     */
    public function getEdit( $id = null ){
        $category = $this->categories->find($id);
        if( !$category )
            return Redirect::to('manage/categories');

        // Determine if the category parent should be disabled or not
        $parentCatAttributes = ( $category->children()->count() > 0 ? array('disabled'=>true) : array() );
        $top_level_categories = ['0'=>'Choose A Parent Category'] + $this->categories->getTopLevel( $id )->lists('name','id');

        return View::make('ProductCatalog::categories.edit')
                    ->with( 'category' , $category )
                    ->with( 'top_level_categories' , $top_level_categories )
                    ->with( 'parentCatAttributes' , $parentCatAttributes );
    }

    /**
     * Accept the input from the new category page
     * @return Redirect
     */
    public function postNew(){
        $entity = new CategoryNew();
        
        if ( $entity->isValid() === false )
            return Redirect::to('manage/categories/new')->withInput()->with( 'errors' , $entity->errors() );
        
        // Hydrate it with data from the POST
        $id = $entity->hydrate();
        return Redirect::to( 'manage/categories/edit/'.$id )->with('success','<strong>Category Added</strong> Fill out the description field to show category specific data on category pages.');
    }

    /**
     * The new product page
     * @access public
     * @return View
     */
    public function getNew(){
        $top_level_categories = ['0'=>'Choose A Parent Category'] + $this->categories->getTopLevel()->lists('name','id');

        return View::make('ProductCatalog::categories.new')
                    ->with( 'top_level_categories' , $top_level_categories );
    }

    /**
     * Edit the product, dayum this rocks
     * @return Redirect
     */
    public function postEdit( $id ){
        $entity = new CategoryEdit( $id );
        
        if ( $entity->isValid() === false )
            return Redirect::to('manage/categories/edit/'.$id)->withInput()->with( 'errors' , $entity->errors() );
        
        // Hydrate it with data from the POST
        $entity->hydrate();
        return Redirect::to( 'manage/categories/' )->with('success','Category Updated.');
    }

}