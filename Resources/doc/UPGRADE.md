# BC breaks and Upgrade notes
# May 2014 Updates
- we added required parameters "attributeClass" and "attributeFieldName" to the controller
 
> update your templates, see ``home.html.twig``

## Pre May 2014 Updates
These are changes we've made since before May of 2014.  In no particular order.

- code for products and attributes is no longer used by default in admin class and templates

> add it yourself if you use it
> this was used in profile and listing_block templates, and the signup form type and templates

- home and about routes are now included in the bundle

> don't need to include in your routing

- "approved" and "spam" fields have been replaces by a single "status" field

> you'll need to update your database

- we create profile and listing_block "content" templates

> you'll need to move customizations there

- we started using validation.yml

> cp validation.yml.dist

- we changed profile and signup routes to "listingProfile" and "listingSignup"

> do a project wide search for these

- we improved error pages

> echo exception.message in error templates

- signup form now looks for an array mapping fieldsets and legends

> add this to your custom form types, look at parent classes for format

- moved use_profiles option to the listing_types option

> update config.yml based on docs

- og_url changed to site_url

> update config.yml

- we created a ListingFormType that's used by the SignupForm

> update your custom form types accordingly
