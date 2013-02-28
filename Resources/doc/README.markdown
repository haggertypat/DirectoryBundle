# CCETC/DirectoryBundle
*This bundle is a work in progress*.

The CCETC/DirectoryBundle is a bundle for building a web-based directory.  It allows users to search/browse listings by category and location.

## Installation
### option 1 - composer
Add to your composer.json:

    "require": {
        "ccetc/directory-bundle": "dev-master"
    }

Run ``php composer.phar install ccetc/directory-bundle``

### option 2 - submodule
Install as a git submodule if you'll be making changes to the bundle:

    git submodule add git@github.com:CCETC/DirectoryBundle.git vendor/ccetc/directory-bundle/CCETC/DirectoryBundle

### add to ``AppKernel.php->registerBundles``

    new CCETC\DirectoryBundle\CCETCDirectoryBundle()

### routes
You must add ``home`` and ``about`` routes to your bundle.

### Config
* bundle_name - name of your bundle - required
* title - used for page title, heading, og tags - required
* logo - used in header - optional
* menu_builder - the main menu to use - optional
* *_template - let you override which templates are used
* copyright - used in footer - optional
* contact_email - used in footer - required
* admin_email - used for e-mail notifications - required
* og_* - used for og meta tags
* google_maps_key - optional
* google_analytics_account - optional

Full config options:

    ccetc_directory:
        bundle_name: MyBundle
        title: My Directory
        logo: bundles/mybundle/images/mylogo.png
        menu_builder: MyBundle:Builder:mainMenu
        layout_template: MyBundle::layout.html.twig
        header_template: MyBundle::_header.html.twig
        footer_template: MyBundle::_footer.html.twig
        profile_template: MyBundle:Directory:profile.html.twig
        listing_block_template: MyBundle:Directory:_listing_block.html.twig
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
### Overriding Templates, and Menu
You can override many templates and the main menu using the config options above.

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

## Custom Pages
You can use a default controller for your pages using this code in your routes:

    defaults: { _controller: CCETCDirectoryBundle:Pages:static, template: MyBundle:Pages:myPage.html.twig }

The default checks for outdated browsers, including a boolean with the result as it renders your template.

## available Twig Globals from config

    directoryTitle
    directoryLogo
    directoryMenuBuilder
    layoutTemplate - all page templates should extend this
    headerTemplate
    footerTemplate
    listingBlockTemplate
    directoryContactEmail
    directoryCopyright
    directoryOgDescription
    directoryOgURL
    googleMapsKey
    googleAnalyticsAccount


## Dependencies
jQuery and Twitter Bootstrap are included in the bundle.

The only other dependencies are ``sonata-project/sonata-admin-bundle`` and ``mopa/bootstrap-bundle``.