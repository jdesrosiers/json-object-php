JsonObject
==========
[![Build Status](https://travis-ci.org/jdesrosiers/json-object-php.png?branch=master)](https://travis-ci.org/jdesrosiers/json-object-php)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/jdesrosiers/json-object-php/badges/quality-score.png?s=df1a8f44a7997e58daa6209bf8c63c6c8c7e5003)](https://scrutinizer-ci.com/g/jdesrosiers/json-object-php/)
[![Code Coverage](https://scrutinizer-ci.com/g/jdesrosiers/json-object-php/badges/coverage.png?s=e50cb1737006b1cf82b70bdbcb6c13802dbf3cbe)](https://scrutinizer-ci.com/g/jdesrosiers/json-object-php/)

Working with JSON is pretty easy with PHP.  PHP data easily decodes from and encodes to JSON.  The problem is that the data has no structure, so we can't enforce any constraints.  Then JSON Schema came along and gave us a universal way to define the structure of JSON data.  So, is it possible to incorporate JSON Schema without loosing the easy integration with PHP?  

Traditional Options
------------------
### JSON Scheam Validator
The easiest way to integrate JSON Schema is to use a JSON Schema validator to check your data.  Ideally, you would want to revalidate every time you modified the data.  But this would require a lot of boiler-plate code and it's too easy to forget to revalidate.

### Generate Classes from JSON Schemas
A more sofisticated approach is to generate a set of classes based on our JSON Schemas.  This poses some challenges, but we should be able to create a library that can convert most JSON Schemas to classes.  This allows us to only define our types once, but our implementation is bloated with a bunch of boiler-plate, generated code.

### Generate JSON Schemas from Classes
Another option is to define our types with classes and generate our JSON Schemas from the the classes.  The problem with this solution is that classes can not describe everything that JSON Schema can.  We can solve some of these deficencies by annotating our classes, but things like data validation can not be expressed.  So, that solution leaves something to be desired as well.

The JsonObject Way
------------------
The goal of JsonObject is to allow us to use JSON Schemas similarly to how we use classes.  Instead of defining your data structures in classes, you define your data structures in JSON Schemas.  Then use the data structures the same way you would any native php type.  This approach would eliminate code duplication when defining data structures and does not require a bunch of boilerplate or generated code.

### Cons
The biggest problem is that I probably will not be able to get these objects to work exactly like native types.  I think I can get it pretty close if I build it as php extension, but that will take some research.  Another problem is that we don't have the benfit of auto-completion and static code checking that we do when we use classes.  But if this concept becomes popular enough, the tools will catch up.

Disclaimer / Credits
--------------------
This is still in the initial stages of development and experimentation to see what is going to work and what isn't.  For now, I am using Geraint Luff's [jsv4](https://github.com/geraintluff/jsv4-php) schema validator under the hood.  At some point I think I am going to have to write my own validator to achieve all of my goals for this project.

