My Agile Product
================
[![Software License](https://img.shields.io/badge/license-GPL v3-green.svg?style=flat)](LICENSE)
[![Build Status](https://travis-ci.org/jfx/my-agile-product.svg?branch=develop)](https://travis-ci.org/jfx/my-agile-product)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jfx/my-agile-product/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/jfx/my-agile-product/?branch=develop)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/641d53ac-f255-49a3-97cc-17c13409f7fc/mini.png)](https://insight.sensiolabs.com/projects/641d53ac-f255-49a3-97cc-17c13409f7fc)

My Agile Product is an open source software to manage functional features of
a product by the description of scenarios.
My Agile Product shows for every release a factual status of the product by 
successful and failed tests passed for each baseline.

My Agile Product features :

* Web interface,
* Multi products,
* Multi users with role,
* Releases and baselines management,
* Features management with editable tree,
* Scenarios and steps management with editable tree,
* Tests management.

- - -
### 0.10.0 ()
Features:

  - Scenario steps parser - #84,
  - View a test - #80,
 

Bugfixes:

  - Fix vertical align of icon in tree - #79,


### 0.9.0 (19 August 2015)
Features:

  - View a scenario - #22,
  - Edit a scenario - #66,
  - Add a scenario - #67,
  - Delete a scenario - #68,
  - New build file for all Jenkins jobs - #69,
  - Database checks in Robot Framework tests for add and delete actions - #71,
  - Change label for form fields to required with asterisk display - #76,
  - Feature count in features tab - #73,
  - Gherkin syntax highlight editor for scenario steps - #27,
  - Color of feature and scenario icons depends on priority or status - #29,
  - Conditional display of add node menu item and remove node button - #74.
 

Bugfixes:

  - Fix constraints for release date and baseline datetime - #70,
  - Checks default values for add feature and scenario forms - #72,
  - Rename narrative feature attribute to description - #75,
  - RF tests : click tree node by id without suffix _anchor - #77,
  - Modal background still present after confirm delete node - #78.


### 0.8.0 (19 July 2015)
Features:

  - View a feature - #39,
  - Edit a feature - #2,
  - Add a feature - #61,
  - Delete a feature - #62,
  - Robot Framework tests improvements - #41.

Bugfixes:

  - Add not blank check for mandatory fields - #65,
  - Minor Robot Framework tests fixes.


### 0.7.0 (31 May 2015)
Features:

  - Refresh tree branch when node is updated - #56,
  - Add a category - #45,
  - Delete a category - #40,
  - Conditional display of tree add and remove buttons - #58,
  - Change URL format of node view and children list - #60.

Bugfixes:

  - Improve exception catched for Ajax response - #57,
  - Migration of $request object usage - #59.


### 0.6.0 (09 May 2015)
Features:

  - Remove all children (Reference and category) when removing a baseline - #47,
  - Unit tests for PasswordFactoryService - #36,
  - Edit a category - #42.

Bugfixes:

  - Remove container in constructor parameter of baseline and release type - #48,
  - Add a default category when adding a baseline - #46,
  - Remove warning in Robot Framework log - #50,
  - Improvement of check jenkins job. Fix PHP_CodeSniffer issues - #53.


### 0.5.0 (07 April 2015)
Features:

  - My Agile Product web site,
  - Migration to symfony 2.6,
  - Update jquery version,
  - Tree view for feature category.

Bugfixes:

  - Clear cache on teardown for timeout tests.


### 0.4.0 (16 December 2014)
Features:

  - Unset user context when logout,
  - Impossible to modify child for a closed release or baseline,
  - Update role in LoginListener.

Bugfixes:

  - Unset user context for admin controllers,
  - Remove objects to delete from all users,
  - Improve catch exception when deleting element,
  - More selective catch exception when thrown not found,
  - Fix class naming conventions for services and abstract classes.
  

### 0.3.0 (10 November 2014)
Features:

  - Design improvements on login page,
  - Check email by IMAP for Robot Framework tests,
  - Improvements of set context for user with RF tests,
  - Select menu by opened baseline with optgroup Product - release.

Bugfixes:

  - Fix scrutinizer major issues,
  - Fix SensioLabs Insight critical and major violations,
  - Fix Error 500 when URL : xxx.localhost/login,
  - Release and baseline data fixtures improvements,
  - Compatibility with PHP 5.6.
  

### 0.2.0 (13 September 2014)
Features:

  - Baseline bundle,
  - Release tab,
  - Migration to Bootstrap v3.2.0,
  - Lessphp from oyejorge,
  - References management,
  - Refactor Info services,
  - Integration in Travis, SensioLabs Insight, Scrutinizer,
  - New green theme.

Bugfixes:

  - Typo fixes,
  - Robot Framework tests fixes,
  - Fix critical SensioLabs violations.


### 0.1.0 (14 August 2014)
Features:

  - Initialization from My Agile Project,
  - Twitter bootstrap v3 theme,
  - Core bundle,
  - Users management,
  - Products management,
  - Roles management,
  - Releases management,
  - Minimal Dashboard.

Bugfixes:

  - 
