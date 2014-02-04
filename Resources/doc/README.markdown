# CCETC/DirectoryBundle
**NOTE: The master branch of the bundle is to be used with Symfony 2.2.  The 2.1 branch is tested with Symfony 2.1**

The CCETC/DirectoryBundle is a bundle for building a web-based directory of "listings". 

Development is tracked on the [trello board](https://trello.com/board/directorybundle/5127a6c2e117a0f56c004854).

## Features
The DirectoryBundle contains everything needed to set up a Symfony app with the following features:

* users can browse listings via a paginated list or a google map
* users can add their own listing pending admin approval
* admins can create/edit/delete/approve listings

It works out of the box with minimal configuration, but most use cases will require a good deal of developer customization.  Most everything in the bundle is easily extendable by the developer.  Common customizations will include:

* added fields
* customized fields available to search by
* added "attributes" (ex: "Attributes" like "Organic" and "Grassfed" for a meat producer directory… Attributes are an Entity with a relationship to Listings)
* customized templates
* customized and added pages
* custom design



## Installation
### Option 1 - Install from Scratch
#### Install a Symfony App
Install your Symfony App using the [Symfony installation guide](http://symfony.com/doc/current/book/installation.html)

Be sure to setup your database as well.

#### Install the DirectoryBundle
Add to your composer.json:

    "require": {
        "ccetc/directory-bundle": "dev-master"
    }

Run ``php composer.phar install``

This will install the bundle and all of it's dependencies.

#### Note about Bootstrap and jQuery
jQuery and Twitter Bootstrap are included in the bundle.  You can include your own copies of either in your bundle, and extend the base layout to include your files instead of the default files.

#### Follow the installation guide for SonataAdmin
<http://sonata-project.org/bundles/admin/master/doc/reference/installation.html>

#### add DirectoryBundle and dependencies to ``AppKernel.php->registerBundles``

    new Knp\Bundle\MenuBundle\KnpMenuBundle(),
    new Sonata\BlockBundle\SonataBlockBundle(),
    new Sonata\jQueryBundle\SonatajQueryBundle(),
    new Sonata\AdminBundle\SonataAdminBundle(),
    new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
    new CCETC\DirectoryBundle\CCETCDirectoryBundle(),
    new MyDirectory\AppBundle\MyDirectoryAppBundle(),
    new Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle(),

*NOTE*: Be sure to add the bundle before your App's bundle, so you can override translations.

#### Make your bundle a "Child" of the DirectoryBundle
To override templates, make your app bundle a child of the DirectoryBundle.  In your bundle's Bundle class (ex: MyBundle.php in the bundle's root dir):

    public function getParent()
    {
        return 'CCETCDirectoryBundle';
    }

#### Config
Add the following to your ``config.yml`` and fill out the values with your app's info:

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
        always_show_advanced_search: false

    sonata_block:
        default_contexts: [cms]
        blocks:
            sonata.admin.block.admin_list:
                contexts:   [admin]
            ccetc.directory.block.admin_listing_approval:
                contexts: [admin]


    sonata_admin:
        templates:
            layout:  CCETCDirectoryBundle::admin_layout.html.twig
        dashboard:
            blocks:
                # display a dashboard block
                - { position: left, type: ccetc.directory.block.admin_listing_approval }
                - { position: left, type: sonata.admin.block.admin_list }

**Note**: The Location Admin classes should not be included on the backend interface.  If using basic HTTP authentication, the easiest way to do this is in your config file, by manually defining which classes *should* appear:

            groups:
              listings:
                label: Listings
                items: [ccetc.directory.admin.listing]
              data:
                label: Data
                items: [ccetc.directory.admin.attribute, ccetc.directory.admin.product]

##### DirectoryBundle config options

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
* always_show_advanced_search - optional, default false
* registration_setting - optional - default none (required|optional|none)


#### Create your Entities
You'll need to create your own entities and extend the base entities and example ``dist`` files provided.  At the very least you will need a ``Listing`` entity that extends ``Base Listing``.

##### Location Entities
If you want to let users search by distance between their location and listing locations, you'll need to extend the ``ListingLocation``, ``LocationDistance``, ``UserLocation``, and ``UserLocationAlias`` entities, using the dist files provided.

##### Attribute Entities
Most installations will have at least one ``Attribute`` related to their ``Listing``.  This could be used to add products (ex: Chicken, Duck, Beef, Pork) and/or actual attributes (ex: Organic, Grass Fed, Hormone Free).  To use this feature, extend ``BaseAttribute`` or use the ``Attribute.php.dist`` file.


#### Create your Admin Classes
You need to add services for the admin classes provided that tie them to your entities.  For each entity that you create, you'll need one of the following, with the path to the entity (argument #2) customized:

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

#### Routing
Add the following to your app's ``routing.yml``: 

	ccetc_directory:
    	resource: "@CCETCDirectoryBundle/Resources/config/routing.yml"
    	prefix:   /


#### Secure your App
You'll want to secure the admin section of your app.  Something like this in ``security.yml``:

	security:
	    firewalls:
	        secured_area:
	            pattern:    ^/
	            anonymous: ~
	            http_basic:
	                realm: "Secured Admin Area"

	    access_control:
	        - { path: ^/admin, roles: ROLE_ADMIN }

	    providers:
	        in_memory:
	            memory:
	                users:
	                    admin: { password: admin, roles: 'ROLE_ADMIN, ROLE_SONATA_ADMIN' }

	    encoders:
	        Symfony\Component\Security\Core\User\User: plaintext

### Option 2 - Install the DirectoryAppTemplate
Follow the instructions on the [DirectoryAppTemplate](https://github.com/CCETC/DirectoryAppTemplate).

## Customization
Since your app is a child bundle of the DirectoryBundle, you can customize most anything, and the following links can help determine how different customizations can be made:

* <http://symfony.com/doc/current/cookbook/bundles/inheritance.html>
* <http://symfony.com/doc/current/cookbook/bundles/override.html>

### User logins and listing editing
There are optional features that create user accounts from the "signup" page, let users edit listings, and let admins manage users.

#### Setup
1. FOSUserBundle and CCETCDirectoryUserBundle are already installed
2. follow FOSUserBundle installation instructions (create User entity in your app bundle, changes to routing.yml, config.yml, security.yml)
3. add CCETCDirectoryUserBundle to the end of AppKernel.  Order matters here for translation customizations:

        new My\AppBundle\MyAppBundle(),
        new FOS\UserBundle\FOSUserBundle(),
        new CCETC\DirectoryBundle\CCETCDirectoryBundle(),
        new CCETC\DirectoryUserBundle\CCETCDirectoryUserBundle(),

4. edit config.yml:

		ccetc_directory:
			registration_setting: required

5. add ROLE_SONATA_ADMIN to ROLE_ADMIN roles in security.yml:

	    security:
	        role_hierarchy:
	            ROLE_ADMIN:       [ROLE_USER, ROLE_SONATA_ADMIN]
	            ROLE_SUPER_ADMIN: ROLE_ADMIN

6. add User/Listing relation:
    
	    In Listing.php:
	
	    /**
	     * @ORM\OneToOne(targetEntity="User", inversedBy="listing")
	     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	     **/
	    private $user;
	
	    In User.php:
	
	    /**
	     * @ORM\OneToOne(targetEntity="BaseListing", mappedBy="user", cascade={"persist", "remove"})
	     **/
	    private $listing; 


7. add admin service:

	    <service id="ccetc.directoryuser.admin.user" class="CCETC\DirectoryUserBundle\Admin\UserAdmin">
	        <tag name="sonata.admin" manager_type="orm" group="Users" label="Users"/>
	        <argument />
	        <argument>My\AppBundle\Entity\User</argument>
	        <argument>CCETCDirectoryUserBundle:UserAdmin</argument>
	    </service>

8. add admin class to config.yml:

        sonata_admin:
            ...
            dashboard:
                ...
                groups:
                  ...
                  users:
                    label: Users
                    items: [ccetc.directoryuser.admin.user]

9. update DB and clear cache!

#### Customizations
- there is an edit form type and handler that can be customized in the same way the Signup form is
- there are edit templates that can be cuomstized in the same way the signup template can be

### User logins and listing editing
There are optional features that create user accounts from the "signup" page, let users edit listings, and let admins manage users.

#### Setup
1. FOSUserBundle and CCETCDirectoryUserBundle are already installed
- follow FOSUserBundle installation instructions (create User entity in your app bundle, changes to routing.yml, config.yml, security.yml)
- add CCETCDirectoryUserBundle to the end of AppKernel.  Order matters here for translation customizations:

        new My\AppBundle\MyAppBundle(),
        new FOS\UserBundle\FOSUserBundle(),
        new CCETC\DirectoryBundle\CCETCDirectoryBundle(),
        new CCETC\DirectoryUserBundle\CCETCDirectoryUserBundle(),

- edit config.yml:

    ccetc_directory:
        registration_setting: required


    registration_setting options:
    - required (users must create an account when adding a listing)
    - optional (users can optionally create an account when additing a listing, can request one later, or edit via a link)
    - none (default - no user accounts create)

- add ROLE_SONATA_ADMIN to ROLE_ADMIN roles in security.yml:

    security:
        role_hierarchy:
            ROLE_ADMIN:       [ROLE_USER, ROLE_SONATA_ADMIN]
            ROLE_SUPER_ADMIN: ROLE_ADMIN

- add User/Listing relation:
    
    In Listing.php:

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="listing")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $user;

    In User.php:

    /**
     * @ORM\OneToOne(targetEntity="BaseListing", mappedBy="user", cascade={"persist", "remove"})
     **/
    private $listing; 


- add admin service:

    <service id="ccetc.directoryuser.admin.user" class="CCETC\DirectoryUserBundle\Admin\UserAdmin">
        <tag name="sonata.admin" manager_type="orm" group="Users" label="Users"/>
        <argument />
        <argument>My\AppBundle\Entity\User</argument>
        <argument>CCETCDirectoryUserBundle:UserAdmin</argument>
    </service>


- add form services:

#### Customizations
- there is an edit form type and handler that can be customized in the same way the Signup form is
- there are edit templates that can be cuomstized in the same way the signup template can be


new trans keys



### Templates
Simply including any of the DirectoryBundle templates in your bundle will override the default templates.

#### Layout
If you'd like to extend the base layout, you'll need to give it a unique name (``app_layout.html.twig``) and set this template path in your config.

### Config and Routing
If you don't want to override the services and routes provided, you should name your routing and config something other than ``routing.yml`` and ``services.xml``.  The alternative is to copy the contents of those files to your own.

### Menu
You can define your own menu class, and override which is used using the config option documented above.

### Translations
You can override translations by copying the ``Resources/translations`` to your bundle.  Make sure your app's bundle is added to AppKernel after the directory bundle or your customizations will not be used.

### Entities
You can add custom fields or field overrides to the entities you create.  Be sure to add your custom fields in the following places:

* Entity class
* Admin class
* Signup form
* Frontend templates

Some useful resources:

* <http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/inheritance-mapping.html>

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

### Validation
Copy ``validation.yml.dist`` to your bundle, and customize as needed.

### Frontend Filters
The directory uses ``ListingAdmin.datagrid`` for the filters on the frontend.  Filters with the option ``isAdvanced`` equal to ``true`` will but put into an "advanced search" section.  Filters with the option 'hideValue' set to true will have their value form input hidden.

### Signup Form
The signup form, form type, and handler exists as services, so you can provide your own form, form type, and/or handler and override the services.  Be sure to override the form template as well.

        <service id="ccetc.directory.form.handler.signup" class="My\AppBundle\Form\Handler\SignupFormHandler" scope="request">
            <argument type="service" id="ccetc.directory.form.signup" />
            <argument type="service" id="request" />
            <argument type="service" id="service_container" />
        </service>

We've implemented a very simple method for automating fieldset/field groups in the form type.  Any customized form type must have a ``getFieldsets`` method that returns an array in the format of ``"Fieldset Label" => array("field1", "field2")``.
    
### Custom Pages
You can use a default controller for your pages using this code in your routes:

    defaults: { _controller: CCETCDirectoryBundle:Pages:static, template: MyBundle:Pages:myPage.html.twig }

The default checks for outdated browsers, including a boolean with the result as it renders your template.

### Multiple Listing Types
We after the initial development added the option to define multiple listing types.  This configuration is optional and the bundle should still work out of the box without any new configuration changes, but this has not been fully tested.

Below are some notes on setting up an app that uses two listing types.  Configuration is fairly simply, and for the most part installation and customization works just as it does with one listing type.

#### Config

    cce_directory:
        listing_type_config:
            - { admin_service: 'ccetc.directory.admin.listinga', entity_class_path: '\My\AppBundle\Entity\ListingA', translation_key: 'listinga' }
            - { admin_service: 'ccetc.directory.admin.listingb', entity_class_path: '\My\AppBundle\Entity\ListingB', translation_key: 'listingb', use_maps: false, use_profiles: false }

##### Options
- admin_service - the service for this type's entity's admin class
- entity_class_path - the path to this type's entity
- translation_key - The translation key will be used in templates, with capitalization and pluralization as needed, so any translations you provide should use this key.  **This key is also used to uniquely identify the listing type - so it must be unique.**
* use_profile - boolean, optional, default: true (if false, profile routes will redirect to the listings page with a single listing)
* use_maps - boolean, optional, default: true (is false, maps tab will not appear on listings page


#### Locations
At the moment, using the Location features is only supported for one listing class/type.  It wouldn't be impossible to implement, but it wasn't needed for our projet, and it was too complicated.  When building your classes, service, class name, and relation field names should still use the word "listing" (ex: ListingLocation is the classname, listings is the relation field, etc).

#### Assumptions
To keep configuration options few, we've made a few assumptions:

- If the listing_block, profile, and signup templates need to be customized between types, their names should follow the format of type->translationKey + "_profile".  If you want to customize a template, but all your types share it, the usual name is fine.
- the three signup services are required and follow the foramt of "ccetc.direction.x.x." + type->translationKey + "signup"

#### Utilities
There some twig variables and functions available:

- listingListingType - the first listing type available (can use if you only have one and need to get a route for example)
- getListingTypeForObject(listing) - returns the listing type for an object that's a listing
- getListingTypeByKey(stringKey) - returns the listing type that matches a translation_key


## Other Features

### available Twig Globals
The following global variables are accessible via any template:

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
		{{ render(controller('CCETCDirectoryBundle:Directory:findAListing', {'includeProducts': false } )) }}
	</div>
