
# Bulk Import

Alfresco supports bulk import of files.  The bulk import system allows you to provide the metadata for those files during the import.  Official Documentation is at:

http://docs.alfresco.com/community/concepts/Bulk-Import-Tool.html

## City of Bloomington files
Most of the files we will import are going to be document types that are custom models we have created in Alfresco.  These files require metadata fields to be provided during import.  This metadata almost always needs to be provided by a human.  Deciphering information from some assumed file naming scheme is fraut with fraught with peril!

### Organize the files
It is easiest to do bulk import of all the files of a single document type at a time.  This simplifies the work for the human providing the metadata.  You should prepare working directories of files that are all of a single type.

There can certainly be lots of subdirectories, and you probably should preserve the original directory structure of the files.  I've been pulling the files from peoples' Showers drives, and work to preserve the directory structure, in Alfresco, that they had, originally, on Showers.

I rsync the files from showers to my local machine, then, for a chosen type, I delete all the local files that are not of that type.   I'm left with a directory of files ready to be imported.  Once imported, I delete my local copy, rsync the originals again, and work on the next document type.

### Prepare a spreadsheet to fill out with metadata
Once you've got a directory of files that are all of the same type, use the prepareSpreadsheet.php script to create a CSV spreadsheet for a human to fill out with the rest of the metadata.  This script will also create the initial XML files for each of the directories.

You will need to edit prepareSpreadsheet.php to point to the working directory of files (of a single type) you've created.  Keep an eye on any hidden files that show up in the files.csv spreadsheet.  These need to be removed from the working directory, and the lines removed from files.csv.  Or you can just make extra sure to delete all unwanted files from the working directory, and just re-run the prepareSpreadsheet.php script.

Make sure any fields you provide are correctly formatted for Alfresco.  For instance, we provide the meeting data for Minutes and Agendas.  The date field must be formatted as yyyy-mm-dd.

### Generate the XML for the files
The createMetadata.php script is written to process all the files in the CSV.  You will need to edit createMetadata.php with the document type in Alfresco that the files should be imported as.

For Meeting files for Boards and Commissions, I've also been appending the meeting time to the date field.  Alfresco only supports providing a full datetime, and it seemed to make more sense than setting the time to midnight.  Make sure you use the correct time format *and timezone*.  Dates will end up displaying on the wrong day, if you set it to midnight UTC, or get the timezone wrong.

### Create/Verify target directories in Alfresco
You should have an Alfresco site in mind for where you want to import the files to.  Make sure the site exists, and that you've got a directory in the documentLibrary ready to hold all of the files.  Subdirectories will be created as needed from the XML that you created.

#### Get the Noderef for the target directory
Using the Noderef is the easiest, safest way to specify the target directory.  You can get it by inspecting the URL of the breadcrumbs while navigating the site's documentLibrary.  The Noderef is a full URI and looks something like:

workspace://SpacesStore/3766dbf1-c9dd-4bd0-8914-c093924029ae


### Import the files.
Once you've generated all the XML metadata for all the files and directories, you'll need to copy the working directory over to the server.  The Alfresco bulk import can only read files from the hard drive of the server.  It does not matter where, on the server, you put the files.  I've just been putting them in my home directory.

Send your browser to:

https://alf.bloomington.in.gov/alfresco/service/bulkfsimport

The import directory is the place on the server's hard drive where you put the files to import.  I recommend using the NodeRef to provide the target space.  These are the only two fields you should have to provide.

### Cleanup
Once imported, you can, and probably should, delete your imported files off the server's hard drive.  Also, you may want to delete your working directory of files from your local machine.  This is especially true if you are importing a directory structure that contains multiple types of files, all in the same directories.  You will need to do a separate pass for each type of file.
