# CCETC/DirectoryBundle
*This bundle is a work in progress*.

The CCETC/DirectoryBundle is a bundle for building a web-based directory of producers.  It allows users to search/browse listings by category and location.

Development is tracked on the [trello board](https://trello.com/board/directorybundle/5127a6c2e117a0f56c004854).

## Installation
Add to your composer.json:

    "require": {
        "ccetc/directory-bundle": "dev-master"
    }

Run ``php composer.phar install``

### add to ``AppKernel.php->registerBundles``

    new CCETC\DirectoryBundle\CCETCDirectoryBundle()

*NOTE*: Be sure to add the bundle before your App's bundle, so you can override translations.

### routes
You must add ``home`` and ``about`` routes to your bundle.

### Config
* bundle_name - name of your bundle - required
* bundle_path - path of your bundle - required
* title - used for page title, heading, og tags - required
* logo - used in header - optional
* menu_builder - the main menu to use - optional
* layout_template - the base template used for all pages
* copyright - used in footer - optional
* contact_email - used in footer - required
* admin_email - used for e-mail notifications - required
* og_* - used for og meta tags
* google_maps_key - optional
* google_analytics_account - optional
* use_profile - boolean, default: true (if false, profile routes will redirect to the listings page with a single listing)
* use_maps - boolean, default: true (is false, maps tab will not appear on listings page
)

Full config options:

    ccetc_directory:
        bundle_name: MyBundle
        bundle_path: \My\Bundle
        title: My Directory
        logo: bundles/mybundle/images/mylogo.png
        menu_builder: MyBundle:Builder:mainMenu
        layout_template: MyBundle::layout.html.twig
        copyright: Our Company 2013
        contact_email: contact@email.com
        admin_email: admin@email.com
        og_description: your description
        og_url: http://yoururl
        google_maps_key: yourkey
        google_analytics_account: UA-NNNNNNNNN-1
        use_profile: true

    sonata_block:
        ...
        blocks:
            ...
            ccetc.directory.block.admin_listing_approval:
                contexts: [admin]

    sonata_admin:
        templates:
            layout:  CCETCDirectoryBundle::admin_layout.html.twig
        dashboard:
            blocks:
                ...
                - { position: left, type: ccetc.directory.block.admin_listing_approval }

**Note**: The Location Admin classes should not be included on the backend interface.  If using basic HTTP authentication, the easiest way to do this is in your config file, by manually defining which classes *should* appear:

            groups:
              listings:
                label: Listings
                items: [ccetc.directory.admin.listing]
              data:
                label: Data
                items: [ccetc.directory.admin.attribute, ccetc.directory.admin.product]



### Entities
Assuming every installation will want to customize the entities, you'll need to create your own and extend the base entities and example ``dist`` files provided. 

### Admin Classes
You need to add services for the admin classes provided that tie them to your entities:

        <service id="ccetc.directory.admin.listing" class="CCETC\DirectoryBundle\Admin\ListingAdmin">
            <tag name="sonata.admin" manager_type="orm" group="Listings" label="Listings"/>
            <argument />
            <argument>Acme\DemoBundle\Entity\Listing</argument>
            <argument>CCETCDirectoryBundle:ListingAdmin</argument>
        </service>
        <service id="ccetc.directory.admin.attribute" class="CCETC\DirectoryBundle\Admin\AttributeAdmin">
            <tag name="sonata.admin" manager_type="orm" group="Data" label="Attributes"/>
            <argument />
            <argument>Acme\DemoBundle\Entity\Attribute</argument>
            <argument>SonataAdminBundle:CRUD</argument>
        </service>
        <service id="ccetc.directory.admin.listinglocation" class="CCETC\DirectoryBundle\Admin\ListingLocationAdmin">
            <tag name="sonata.admin" manager_type="orm" group="Location Data" label="Listing Locations"/>
            <argument />
            <argument>Acme\AppBundle\Entity\ListingLocation</argument>
            <argument>SonataAdminBundle:CRUD</argument>
        </service>
        <service id="ccetc.directory.admin.userlocation" class="CCETC\DirectoryBundle\Admin\UserLocationAdmin">
            <tag name="sonata.admin" manager_type="orm" group="Location Data" label="User Locations"/>
            <argument />
            <argument>Acme\DemoBundle\Entity\UserLocation</argument>
            <argument>SonataAdminBundle:CRUD</argument>
        </service>
        <service id="ccetc.directory.admin.userlocationalias" class="CCETC\DirectoryBundle\Admin\UserLocationAliasAdmin">
            <tag name="sonata.admin" manager_type="orm" group="Location Data" label="User Location Aliases"/>
            <argument />
            <argument>Acme\DemoBundle\Entity\UserLocationAlias</argument>
            <argument>SonataAdminBundle:CRUD</argument>
        </service>        
        <service id="ccetc.directory.admin.locationdistance" class="CCETC\DirectoryBundle\Admin\LocationDistanceAdmin">
            <tag name="sonata.admin" manager_type="orm" group="Location Data" label="Location Distances"/>
            <argument />
            <argument>Acme\DemoBundle\Entity\LocationDistance</argument>
            <argument>SonataAdminBundle:CRUD</argument>
        </service>

*Note*: ``ListingAdmin`` should use the custom controller (``CCETCDirectoryBundle:ListingAdmin``) from the bundle.

### Validation
Copy ``validation.yml.dist`` to your bundle, and customize as needed.

## Customization
### Child Bundle
To override templates, make your app bundle a child of the DirectoryBundle:

    public function getParent()
    {
        return 'CCETCDirectoryBundle';
    }


#### Layout
If you'd like to extend the base layout, you'll need to give it a unique name (``app_layout.html.twig``) and set this template path in your config.

#### Config and Routing
If you're using the bundle as a parent bundle, and don't want to override the services and routes provided, you should name your routing and config something other than ``routing.yml`` and ``services.xml``.  The alternative is to copy the contents of those files to your own.

### Menu
You can override the main menu using the config options above.

### Translations
You can override translations by copying the ``Resources/translations`` to your bundle.  Make sure your app's bundle is added to AppKernel after the directory bundle or your customizations will not be used.

### Entities
You can add custom fields or field overrides to the entities you create.  See http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/inheritance-mapping.html

### Admin Classes
You can extend the provided admin classes:

    use CCETC\DirectoryBundle\Admin\ListingAdmin as BaseListingAdmin;

    class ListingAdmin extends BaseListingAdmin
    {
        protected function configureFormFields(FormMapper $formMapper)
        {
            parent::configureFormFields($formMapper);

            $formMapper
                ->with('Products')
                    ->add('myField')
                ->end()
            ;
        }
    }

**Note**: Make sure that any entities or fields not used by your front end do not appear on your signup form or admin classes

### Frontend Filters
The directory uses ``ListingAdmin.datagrid`` for the filters on the frontend.  Filters with the option ``isAdvanced`` equal to ``true`` will but put into an "advanced search" section.

### Signup Form
The signup form and handler exists as services, so you can provide your own form and/or handler and override the services.  Be sure to override the form template as well.

        <service id="ccetc.directory.form.handler.signup" class="My\AppBundle\Form\Handler\SignupFormHandler" scope="request">
            <argument type="service" id="ccetc.directory.form.signup" />
            <argument type="service" id="request" />
            <argument type="service" id="service_container" />
        </service>
    
## Custom Pages
You can use a default controller for your pages using this code in your routes:

    defaults: { _controller: CCETCDirectoryBundle:Pages:static, template: MyBundle:Pages:myPage.html.twig }

The default checks for outdated browsers, including a boolean with the result as it renders your template.

## available Twig Globals from config

    directoryTitle
    directoryLogo
    directoryMenuBuilder
    layoutTemplate - all page templates should extend this
    directoryContactEmail
    directoryCopyright
    directoryOgDescription
    directoryOgURL
    googleMapsKey
    googleAnalyticsAccount


## Find a Listing Block
You can include the find a listing block in your pages.  Just make sure to wrap it in a div with the class ``find-a-listing``:

    <div class="find-a-listing alert alert-block alert-info">
        {% render 'CCETCDirectoryBundle:Directory:findAListing' %}    
    </div>

## Dependencies
jQuery and Twitter Bootstrap are included in the bundle.

The only other dependencies are ``sonata-project/sonata-admin-bundle`` and ``mopa/bootstrap-bundle``.
