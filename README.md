# Orbis Cascade Alliance Institutional Slip Printer

This application gets and prints Alma letters from a printout queue.

## Setup

Download and install all files of this application on a PHP server. The PHP installation must have libcurl (for [cURL](https://www.php.net/manual/en/curl.requirements.php)) and libxml (for [SimpleXML](https://www.php.net/manual/en/simplexml.requirements.php)) installed and enabled.

In the [Ex Libris Developer Network](https://developers.exlibrisgroup.com/), create an API key with the following permissions in your production environment:

- Configuration, Read-only (used to get print queues)
- Task-lists, Read/write (used to get printouts and modify their statuses)

In the application file functions.php:

1) Enter your API key in the definition of API_KEY, inside the empty set of single quotes.
2) In SLIPS_PER_PAGE, set the default number of columns to fit on one page. (Default: 4)
3) In SLIP_LIMIT, define the maximum number of printouts to get per API request. (Default and API maximum: 100)

## Use

To send pick-up slips to a printout queue, navigate in Alma to Fulfillment > Resource Requests > Pick From Shelf. Select the checkboxes next to records you'd like to send, and click "Print Slip" in the top right. This will generate the Ful Resource Request Letter for each of the requests and send them to the default queue for your current location. 
(This application can also print other letters like Ful Incoming Request, generated from the Lending Requests screen, but the styles in slips.css assume you're using the Ful Resource Request Letter formatted centrally by the Orbis Cascade Alliance.)

You can view all pending, printed, or canceled slips in the queue under Admin > Printing > Printouts Queue.

In the application, select the printout queue and click "Get Slips." The printouts in the queue with a status of "Pending" will populate in a gray box below. By default, all printouts will be selected.

You can change the number of slips printed per page using the dropdown on the right. The gray box will update to show an approximate preview of what the slips will look like on regular office paper in landscape mode. Printer settings will affect the final result.

Select the printouts to print and click "Go." This will create a new window containing only the selected slips, and the print dialog for your browser will open. Print the document to your preferred printer, or cancel if needed.

After the print window closes, a dialog will prompt you to mark the slips as printed. If you successfully printed the slips, select "Yes," and the printouts in the queue will be updated from "Pending" to "Printed." If you did not print the slips or want to print them again, select "No" to retain the "Pending" status.

You can also select "Cancel" instead of "Print" from the dropdown. On clicking "Go," a dialog will prompt you to confirm you want to mark the selected printouts as canceled.

Once printouts are marked either "Printed" or "Canceled," they cannot be reverted in Alma. They must be sent to the queue again from the pick list.

## Structure

This application is designed to be a starting point. Customize any of the files below to fit the needs of your institution. **In particular, a library should add a robust authentication layer to all PHP files to protect patron privacy.**

- *functions.php:* Contains definitions and common PHP functions for getting responses from the APIs.
- *index.php:* The webpage for getting and printing slips.
- *loading.gif:* This image displays when "Get Slips" has been triggered but the slips.php hasn't yet responded.
- *mark.php:* This script is called asynchronously to mark the selected printouts as either "Canceled" or "Printed."
- *print.css:* Styles the webpage generated to print selected slips.
- *script.js:* Contains all JavaScript functions for the webpage.
- *slip.css:* Styles slip contents. This file is imported by both style.css for display on the webpage and print.css for printing.
- *slips.php:* This script is called asynchronously when the user clicks "Get Slips." It returns the form element containing the selection and action options and the HTML of all slips.
- *style.css:* Styles index.php.

