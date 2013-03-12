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

### add to ``AppKernel.php->registerBundles`` *before* your bundle

    new CCETC\DirectoryBundle\CCETCDirectoryBundle()

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

### Entities
You need to create your entities by extending the base entities provided.  ``CCETCDirectoryBundle/Entity`` has ``dist`` classes that you can use.

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
        <service id="ccetc.directory.admin.product" class="CCETC\DirectoryBundle\Admin\ProductAdmin">
            <tag name="sonata.admin" manager_type="orm" group="Data" label="Products"/>
            <argument />
            <argument>Acme\DemoBundle\Entity\Product</argument>
            <argument>SonataAdminBundle:CRUD</argument>
        </service>

*Note*: ``ListingAdmin`` should use the custom controller (``CCETCDirectoryBundle:ListingAdmin``) from the bundle.

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
You can override translations by copying the ``Resources/translations`` to your bundle.

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