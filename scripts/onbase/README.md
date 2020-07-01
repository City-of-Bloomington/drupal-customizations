I've been working through the functionality needed to integrate Drupal and OnBoard with OnBase.  Implementing this functionality impacts the fields of metadata for documents. Before we can import documents from these systems, we must know how we will implement these in OnBase.

OnBase must be set up to:
* Upload files to web server on document update
* Store a public url for each file
* Display public url to users in the web client
* provide a Web API to add and update documents

Centralized document management drives the integration features we need
=======================================================================
In order to publish files to city websites, staff must not download the files from OnBase, then upload them again into various places, because when they are updated in OnBase, all those copies are out of date and staff do not remember where they are.  For example, this happens often with PDF forms that change often over time.  We often have problems where citizens use an out of date PDF where staff forgot about the link.

Instead, OnBase will provide the canonical place for every file. Because OnBase cannot serve files directly to citizens, OnBase must push the files onto a static web server as they are updated.  Websites and web applications must use the canonical URL for these files.  These urls might be automatically provided to web sites via integration, but staff could also copy and paste the URLs as needed.


OnBase must know the public hosting of files
------------------------------------------------
Files, particularly images, must be served publically quickly and effeciently.  OnBase cannot fill this role; so, public files will be hosted on a static file web server.  I have already set up a web server to fill this role.

htts://static.bloomington.in.gov

OnBase must know the base url to these static files, as well as knowing an agreed upon directory structure and file naming scheme.  Once OnBase can display the URL, staff will use the OnBase web client to find the file they want to publish, then copy and paste the URL into the city website.

We need a way to denote whether a document is public or not.  This public/non-public determination is independent of document type.  Staff must be able to unpublish files while keeping them around in OnBase.  When a public document is updated, OnBase should upload a fresh copy of the file to the web server and update a keyword field with the URL for that file.

We may be able to use the presence or absence of the public URL value to denote whether the file is public or not.  We would likely need a button on the screen for the user to push that would activate the workflow to copy the file and generate the URL.

We also need a button or something to unpublish files.  OnBase would delete the file from the web server and empty out the URL.


OnBoard should be able to query OnBase using a web api
------------------------------------------------------
OnBoard manages boards and commissions; having all aspects of a board or committee in one place is useful for staff and gives citizens a place to follow city actions.  Other web applications, like the city website, use OnBoard as the canonical source of all information about boards and committees.

Ideally, staff will use OnBoard to manage meetings documents, legislation, and reports.  OnBoard would add and update the documents in OnBase via OnBase's web api.

If we rely on an OnBase docpop page to list these files in OnBoard, then OnBoard must know how to construct these URLS in order to send citizens to those pages.

https://documents.bloomington.in.gov/AppNet/docpop/docpop.aspx?clienttype=html&cqid=104

We could also (or instead) have OnBoard generate this information (as HTML, json, xml) by using OnBase's web api to get the necessary information for the documents.  Then OnBoard would generate the HTML pages, using each document's public URL for the links.
