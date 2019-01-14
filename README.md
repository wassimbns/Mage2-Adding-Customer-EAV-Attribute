# Mage2-Adding-Customer-EAV-Attribute

It addition to Magento_Customer module will be usefull if you want to add an custom attribute to customer's informations.

In this Module I added a boolean type attribute to indicate wether the customer have patent ot not.

You can fetch the value of that attribute via the function hasPatent() which returns true or false (have or not).

This function can be accessible where ever you need it. All you have to do inject the Helper file in your constructor. 


So adjust your module version and Hit that => php bin/magento setup:upgrade    

And get started...
