=== Ads Benedict ===
Contributors: binarygary
Tags: banner ads, easy ads, double click for publishers, ad network
Stable Tag: 0.3.0
Requires at least: 4.0
Tested up to: 4.5
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl.html

This is a super basic banner ad plugin.  CPM? CPC? CPX? CPR? Nope...  If you need to have a banner or banners displayed in multiple spots, this is it.  

== Description ==

I run ads for long periods of time so I don't care about accurate display statistics or anything of that nature.
I needed to show banner ads on my site, but double click for publishers was messing up my template and other plugins were too heavy for what I needed.
So, I made a super-basic banner plugin and named it after my favorite breakfast.

Create a banner, paste the link it should point to, name the zone you want it in, and make a note of the advertiser.
Then put the shortcode into your template or directly in a post.  

If you put multiple banners into one zone then 1 will randomly be selected when the page loads.

== Installation ==

1. Install from repository
2. Update your template `<?php do_shortcode('[adsbenedict zone=zonename]') ?>` (change the zone name to whatever ads you want displayed here)
3. In posts/pages just use shortcode: `[adsbenedict zone=zonename]` (change the zone name to whatever ads you want displayed here)
4. Go to the adsbenedict menu page and add some ads.  

== Frequently Asked Questions ==

= Aren't there enough bad banner ad plugins? =

Yep.  And this one makes no claims to superiority other than that it is insanely simple and fixes some problems I was having with other ad plugins. 

= What about click tracking? weighting? alternate networks? =

If you use YOURLS link shortener you can have some rudimentary tracking.  Set your YOURLS address and secret token and a column will be added that keeps track of statistics.
There is no protection against fraudulent clicks.
I use this on ads that are sold and run for a period of time as opposed to ads that have a certain number of impressions or clicks associated with them.  

= Will it ever... =

The only "features" I *think* I'm going to add are a start date.
Subject to change as I need to add features for myself.

= Can I request a feature? =

Of course.  If it's interesting or seems helpful to the rest of the world I might add it.  

= Does it handle billing? =

Nope.

= If I put the same zone in my template twice could it possibly display the same ad twice? =

Yes.

== Screenshots ==
1. If you choose to use an install of YOURLS as a means of keeping track of clicks, this the setup page.
2. Ah...where the magic happens. This is a view of the live ads.  Editing/Adding is just like editing/adding a post in WordPress. Lovely.
3. And...a shortcode.  If you were using ads within a post you'd do it this way.  Change the zone to whatever zone your ads are in.  Were you to want to use this inside a theme something like: <?php do_shortcode('[adsbenedict zone=zonename]'); ?> would be a better choice.

== Changelog ==

= 0.3.0 =
* Fixed a slow query related to the expiration post_meta.

= 0.2.8 =
* Increased quantity of ads to include in array.

= 0.2.7 =
* Fixed formula for ad conversion calculations.  

= 0.2.6 =
* Forgot to make the ajax run for non-logged in users in previous release.

= 0.2.5 =
* Ads can be loaded via ajax which defeats full page caching.

= 0.2.4 =
* Speed and logic improvements in ad performance generation

= 0.2.3 =
* Fixed main shortcode return so PHP notice is not raised

= 0.2.2 =
* Fixed enqueued script with harcoded protocol 
* Fixed error where YOURLS was passed an empty string

= 0.2.1 =
* Added short term caching to prevent slow loading due to long running query on sites with lots of posts

= 0.2.0 =
* Added expiration date.
* For already existing ads, this will not impact display.
* New ads will default to an expiration one year from date of creation.
* Added column to show expiration date.
* Use yourls API to display statistics.  
* Check to see if yourls shortener is in use before adding display column of stats.

= 0.1.3 =
* Fixed YOURLS error is trying to use an already shortened link (please don't do that)
* Styled a shortened link so it's responsive.  Hardcoded for now.

= 0.1.2 =
* Added YOURLS integration
* With YOURLS integration clicks and impressions are captures

= 0.1 
* This is the version I'm using on my sites.  
* Removed most (all?) self-deprecating comments.
