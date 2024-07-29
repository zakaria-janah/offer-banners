# Magento 2 Module: Offer Banners Management

## Overview

This Magento 2 module allows administrators to manage offer banners from the back office and display them on category pages. This document provides a comprehensive guide on installing, configuring, and using the module.

## Features

- Back Office Management: Create, edit, and delete offer banners.
- Category Page Integration: Display banners on category pages.
- Flexible Configuration: Assign banners to specific categories.

## Installation

1. Download the module: Obtain the module package from the repository.
2. Upload the module: Upload the module files to the Magento root directory under app/code/Dnd/Offers.

## Configuration

1. Access the module configuration:
	- Go to 'Stores' > 'Configuration' > 'Dnd Extensions' > 'Offers'.
2. General Settings:
	- Enable Module: Enable or disable the module.
3. And run:
	> php bin/magento setup:upgrade<br/>
	> php bin/magento setup:di:compile<br/>
	> php bin/magento setup:static-content:deploy<br/>
	> php bin/magento cache:clean<br/>
	> php bin/magento cache:flush<br/>

## Managing Offer Banners

1. Navigate to Offer Banners:
	- Go to 'Dnd Offers'.
2. Add a New Offer:
	- Click on 'Create New Offer'.
	- Fill in the details including offer name, image, and link... .
	- Assign the offer to specific categories.
3. Edit an Existing Offer:
	- Click on the offer you wish to edit.
	- Update the necessary details.
	- Save the changes.
4. Delete an Offer:
	- Select the offer(s) you wish to delete.
	- Click on Delete in the actions dropdown.

## Displaying Offers on Category Pages

The offers will automatically appear on the assigned category pages based on the configuration set in the back office. Ensure the module is enabled and properly configured.
