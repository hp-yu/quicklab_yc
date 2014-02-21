<?php
/*
Dim databaseDir, connStr, Conn, userId
databaseDir = "D:\wwwroot\mmartins.com\www\database"
connStr = "DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=" & databaseDir & "\favorites.mdb"

'Note: databaseDir is a directory path, not an URL (not a web address)

Set Conn = Server.CreateObject("ADODB.Connection")
userId = 0

*/
//######## USER AUTHENTICATION ###########
/*
function authorizedLogin()
	authorizedLogin  = ""
	if request.cookies("bookmarks_user_id") = "" then 
		authorizedLogin = "User authentication is needed.<br>"
	else
		userId = request.cookies("bookmarks_user_id")
		Conn.Open(connStr)
	end if
end function
*/
/*
' The function creates a folder or a regular bookmark node and returns the id for the
' just created node.
' The first three arguments correspond to what the user would enter in the web page.
' "nodeIsfolder" is a boolean that controls whether you are creating a folder or a regular bookmark node
' "parent" indicates where the new folder or bookmarks is going to be located. To put a folder in the 
' root, give it a parent of value -1. Otherwise use the id returned by the function for the node
' inside of which you want to create a new node.
' "user" is the id of the current user
*/
function addNode($nodename, $comments, $boxsize, $isbox, $parent,$user)
{
	$db_conn = db_connect();
	$query = "insert into location 
            (name, pid, isbox, box_size)
          values 
            ('$nodename', '$parent', '$isbox', '$boxsize')"; 
  	$result = $db_conn->query($query);
  	/*
	Dim rsAdd
	Set rsAdd = Server.CreateObject("ADODB.Recordset")
	rsAdd.Open "bookmarksTree", Conn, adOpenKeyset, adLockOptimistic
	rsAdd.AddNew
	rsAdd.Fields("user") = user
	rsAdd.Fields("nodeName") = nodeName
	rsAdd.Fields("parent") = parent
	rsAdd.Fields("nodeIsFolder") = nodeIsFolder
	rsAdd.Fields("link") = link
	rsAdd.Fields("notes") = comments
	rsAdd.Update	
	addNode = rsAdd.Fields("nodeId")
	rsAdd.Close*/
}
/*
function getNewUserId()
	Dim rsAdd, auxNodeId
	Set rsAdd = Server.CreateObject("ADODB.Recordset")
	'Add user
	rsAdd.Open "bookmarksUser", Conn, adOpenKeyset, adLockOptimistic
	rsAdd.AddNew
	rsAdd("username") = "guest"
	rsAdd("dateCreated") = Now()
	rsAdd("lastLogin") = Now()
	rsAdd("numberOfLogins") = 0
	rsAdd.Update
	getNewUserId = rsAdd("bookmarks_user_id")
	rsAdd.close
	'Add bookmarks folder
	auxNodeId = addNode ("Bookmarks", "Add links and subfolders - use the toolbar at the top", "", True, -1, getNewUserId)
	addNode "Cable News Network", "just an example", "http://www.cnn.com", False, auxNodeId, getNewUserId
	addNode "NY Times", "notes go here", "http://www.nytimes.com", False, auxNodeId, getNewUserId
	auxNodeId = addNode("Other", "example of subfolder", "", True, auxNodeId, getNewUserId)
	addNode "Treeview.net", "Treeview and FavoritesManagerASP", "http://www.treeview.net", False, auxNodeId, getNewUserId
	addNode "WebmailASP", "Access your POP email from anywhere", "http://www.webmailasp.net", False, auxNodeId, getNewUserId
end function
*/
/*
function updateUser(uId)
	Dim rsAdd
	Set rsAdd = Server.CreateObject("ADODB.Recordset")
	updateUser = True 'successful
	'Update user
	rsAdd.Open "SELECT * from bookmarksUser WHERE (bookmarks_user_id = "&uId&")", Conn, adOpenKeyset, adLockOptimistic
	if rsAdd.EOF then
		updateUser = False
	else
		rsAdd("lastLogin") = Now()
		rsAdd("numberOfLogins") = rsAdd("numberOfLogins") +1
		rsAdd.Update
	end if
	rsAdd.close
end function
*/
/*
function updateOrCreateUser() 'returns true if a new user was created
	Dim rsAdd, ok
	Set Conn = Server.CreateObject("ADODB.Connection")
	Conn.Open(connStr)

	if request.querystring("set_bookmarks_user_id") <> "" then 
		if request.querystring("set_bookmarks_user_id") = "new" then
			userId = getNewUserId()
			response.cookies("bookmarks_user_id") = userId
			response.cookies("bookmarks_user_id").Expires = #March 10, 2010#
			updateOrCreateUser = true
		else
			response.cookies("bookmarks_user_id") = request.querystring("set_bookmarks_user_id")
			response.cookies("bookmarks_user_id").Expires = #March 10, 2010#
			userId  = request.querystring("set_bookmarks_user_id")
			updateOrCreateUser = false
		end if
	else
		if request.cookies("bookmarks_user_id") = "" then
			userId = getNewUserId()
			response.cookies("bookmarks_user_id") = userId
			response.cookies("bookmarks_user_id").Expires = #March 10, 2010#
			updateOrCreateUser = true
		else
			ok = updateUser(request.cookies("bookmarks_user_id"))
			if not ok then
				userId = getNewUserId()
				response.cookies("bookmarks_user_id") = userId
				response.cookies("bookmarks_user_id").Expires = #March 10, 2010#
				updateOrCreateUser = true
			else
				userId = request.cookies("bookmarks_user_id")
				updateOrCreateUser = false
			end if
		end if
	end if
	Conn.Close
end function
*/


//########  AUX FUNCTIONS ################
Function Subst($initialStr, $oldStr, $newStr)
{
	//Dim regEx
    If (is_null($initialStr) ||  strlen($initialStr) == 0)
        return "";
    ElseIf (is_null($oldStr) || strlen($oldStr) == 0 )
        return $initialStr;
    Else
    {
      If (is_null($newStr) )
      {
        $newStr = "";
      }
        $Str=str_replace($oldStr,$newStr,$initialStr);
        return $Str;
        /*
		Set regEx = New RegExp   ;
		regEx.Pattern = oldStr   ;
		regEx.IgnoreCase = True;
		regEx.Global = True;
		Subst = regEx.Replace(initialStr, newStr)   ;
		*/
    }
}

function FixUpItems ($strItem)
{
	if (is_null($strItem))
	  return '';
	elseif ($strItem != '' )
	  $strItem = Subst($strItem, "<", ".lt;");
	  $strItem = Subst($strItem, ">", ".gt;");
	  $strItem = Subst($strItem, "", ".quot;");
	  $strItem = Subst($strItem, "'", ".#39;");
	  $strItem = Subst($strItem, "\+", ".#43;") ;
	  //this text causes all kinds of trouble: +351-214 258 600 ...;
	  return $strItem;
}
/*
sub debugprint (strArg)
	if IsNull(strArg) then
		response.write "- null value -<br>" 
	else
		response.write "-" & server.HTMLEncode(strArg) & "-<br>" 
	end if
end sub
*/
/*
'--------------------------------------------------------------------
' Microsoft ADO
'
' Copyright (c) 1996-1998 Microsoft Corporation.
'
'
'
' ADO constants include file for VBScript
'
'--------------------------------------------------------------------

'---- CursorTypeEnum Values ----
Const adOpenForwardOnly = 0
Const adOpenKeyset = 1
Const adOpenDynamic = 2
Const adOpenStatic = 3

'---- CursorOptionEnum Values ----
Const adHoldRecords = &H00000100
Const adMovePrevious = &H00000200
Const adAddNew = &H01000400
Const adDelete = &H01000800
Const adUpdate = &H01008000
Const adBookmark = &H00002000
Const adApproxPosition = &H00004000
Const adUpdateBatch = &H00010000
Const adResync = &H00020000
Const adNotify = &H00040000
Const adFind = &H00080000
Const adSeek = &H00400000
Const adIndex = &H00800000

'---- LockTypeEnum Values ----
Const adLockReadOnly = 1
Const adLockPessimistic = 2
Const adLockOptimistic = 3
Const adLockBatchOptimistic = 4

'---- ExecuteOptionEnum Values ----
Const adAsyncExecute = &H00000010
Const adAsyncFetch = &H00000020
Const adAsyncFetchNonBlocking = &H00000040
Const adExecuteNoRecords = &H00000080

'---- ConnectOptionEnum Values ----
Const adAsyncConnect = &H00000010

'---- ObjectStateEnum Values ----
Const adStateClosed = &H00000000
Const adStateOpen = &H00000001
Const adStateConnecting = &H00000002
Const adStateExecuting = &H00000004
Const adStateFetching = &H00000008

'---- CursorLocationEnum Values ----
Const adUseServer = 2
Const adUseClient = 3

'---- DataTypeEnum Values ----
Const adEmpty = 0
Const adTinyInt = 16
Const adSmallInt = 2
Const adInteger = 3
Const adBigInt = 20
Const adUnsignedTinyInt = 17
Const adUnsignedSmallInt = 18
Const adUnsignedInt = 19
Const adUnsignedBigInt = 21
Const adSingle = 4
Const adDouble = 5
Const adCurrency = 6
Const adDecimal = 14
Const adNumeric = 131
Const adBoolean = 11
Const adError = 10
Const adUserDefined = 132
Const adVariant = 12
Const adIDispatch = 9
Const adIUnknown = 13
Const adGUID = 72
Const adDate = 7
Const adDBDate = 133
Const adDBTime = 134
Const adDBTimeStamp = 135
Const adBSTR = 8
Const adChar = 129
Const adVarChar = 200
Const adLongVarChar = 201
Const adWChar = 130
Const adVarWChar = 202
Const adLongVarWChar = 203
Const adBinary = 128
Const adVarBinary = 204
Const adLongVarBinary = 205
Const adChapter = 136
Const adFileTime = 64
Const adPropVariant = 138
Const adVarNumeric = 139
Const adArray = &H2000

'---- FieldAttributeEnum Values ----
Const adFldMayDefer = &H00000002
Const adFldUpdatable = &H00000004
Const adFldUnknownUpdatable = &H00000008
Const adFldFixed = &H00000010
Const adFldIsNullable = &H00000020
Const adFldMayBeNull = &H00000040
Const adFldLong = &H00000080
Const adFldRowID = &H00000100
Const adFldRowVersion = &H00000200
Const adFldCacheDeferred = &H00001000
Const adFldIsChapter = &H00002000
Const adFldNegativeScale = &H00004000
Const adFldKeyColumn = &H00008000
Const adFldIsRowURL = &H00010000
Const adFldIsDefaultStream = &H00020000
Const adFldIsCollection = &H00040000

'---- EditModeEnum Values ----
Const adEditNone = &H0000
Const adEditInProgress = &H0001
Const adEditAdd = &H0002
Const adEditDelete = &H0004

'---- RecordStatusEnum Values ----
Const adRecOK = &H0000000
Const adRecNew = &H0000001
Const adRecModified = &H0000002
Const adRecDeleted = &H0000004
Const adRecUnmodified = &H0000008
Const adRecInvalid = &H0000010
Const adRecMultipleChanges = &H0000040
Const adRecPendingChanges = &H0000080
Const adRecCanceled = &H0000100
Const adRecCantRelease = &H0000400
Const adRecConcurrencyViolation = &H0000800
Const adRecIntegrityViolation = &H0001000
Const adRecMaxChangesExceeded = &H0002000
Const adRecObjectOpen = &H0004000
Const adRecOutOfMemory = &H0008000
Const adRecPermissionDenied = &H0010000
Const adRecSchemaViolation = &H0020000
Const adRecDBDeleted = &H0040000

'---- GetRowsOptionEnum Values ----
Const adGetRowsRest = -1

'---- PositionEnum Values ----
Const adPosUnknown = -1
Const adPosBOF = -2
Const adPosEOF = -3

'---- BookmarkEnum Values ----
Const adBookmarkCurrent = 0
Const adBookmarkFirst = 1
Const adBookmarkLast = 2

'---- MarshalOptionsEnum Values ----
Const adMarshalAll = 0
Const adMarshalModifiedOnly = 1

'---- AffectEnum Values ----
Const adAffectCurrent = 1
Const adAffectGroup = 2
Const adAffectAllChapters = 4

'---- ResyncEnum Values ----
Const adResyncUnderlyingValues = 1
Const adResyncAllValues = 2

'---- CompareEnum Values ----
Const adCompareLessThan = 0
Const adCompareEqual = 1
Const adCompareGreaterThan = 2
Const adCompareNotEqual = 3
Const adCompareNotComparable = 4

'---- FilterGroupEnum Values ----
Const adFilterNone = 0
Const adFilterPendingRecords = 1
Const adFilterAffectedRecords = 2
Const adFilterFetchedRecords = 3
Const adFilterConflictingRecords = 5

'---- SearchDirectionEnum Values ----
Const adSearchForward = 1
Const adSearchBackward = -1

'---- PersistFormatEnum Values ----
Const adPersistADTG = 0
Const adPersistXML = 1

'---- StringFormatEnum Values ----
Const adClipString = 2

'---- ConnectPromptEnum Values ----
Const adPromptAlways = 1
Const adPromptComplete = 2
Const adPromptCompleteRequired = 3
Const adPromptNever = 4

'---- ConnectModeEnum Values ----
Const adModeUnknown = 0
Const adModeRead = 1
Const adModeWrite = 2
Const adModeReadWrite = 3
Const adModeShareDenyRead = 4
Const adModeShareDenyWrite = 8
Const adModeShareExclusive = &Hc
Const adModeShareDenyNone = &H10
Const adModeRecursive = &H400000

'---- RecordCreateOptionsEnum Values ----
Const adCreateCollection = &H00002000
Const adCreateStructDoc = &H80000000
Const adCreateNonCollection = &H00000000
Const adOpenIfExists = &H02000000
Const adCreateOverwrite = &H04000000
Const adFailIfNotExists = -1

'---- RecordOpenOptionsEnum Values ----
Const adOpenRecordUnspecified = -1
Const adOpenSource = &H00800000
Const adOpenAsync = &H00001000
Const adDelayFetchStream = &H00004000
Const adDelayFetchFields = &H00008000

'---- IsolationLevelEnum Values ----
Const adXactUnspecified = &Hffffffff
Const adXactChaos = &H00000010
Const adXactReadUncommitted = &H00000100
Const adXactBrowse = &H00000100
Const adXactCursorStability = &H00001000
Const adXactReadCommitted = &H00001000
Const adXactRepeatableRead = &H00010000
Const adXactSerializable = &H00100000
Const adXactIsolated = &H00100000

'---- XactAttributeEnum Values ----
Const adXactCommitRetaining = &H00020000
Const adXactAbortRetaining = &H00040000

'---- PropertyAttributesEnum Values ----
Const adPropNotSupported = &H0000
Const adPropRequired = &H0001
Const adPropOptional = &H0002
Const adPropRead = &H0200
Const adPropWrite = &H0400

'---- ErrorValueEnum Values ----
Const adErrProviderFailed = &Hbb8
Const adErrInvalidArgument = &Hbb9
Const adErrOpeningFile = &Hbba
Const adErrReadFile = &Hbbb
Const adErrWriteFile = &Hbbc
Const adErrNoCurrentRecord = &Hbcd
Const adErrIllegalOperation = &Hc93
Const adErrCantChangeProvider = &Hc94
Const adErrInTransaction = &Hcae
Const adErrFeatureNotAvailable = &Hcb3
Const adErrItemNotFound = &Hcc1
Const adErrObjectInCollection = &Hd27
Const adErrObjectNotSet = &Hd5c
Const adErrDataConversion = &Hd5d
Const adErrObjectClosed = &He78
Const adErrObjectOpen = &He79
Const adErrProviderNotFound = &He7a
Const adErrBoundToCommand = &He7b
Const adErrInvalidParamInfo = &He7c
Const adErrInvalidConnection = &He7d
Const adErrNotReentrant = &He7e
Const adErrStillExecuting = &He7f
Const adErrOperationCancelled = &He80
Const adErrStillConnecting = &He81
Const adErrInvalidTransaction = &He82
Const adErrUnsafeOperation = &He84
Const adwrnSecurityDialog = &He85
Const adwrnSecurityDialogHeader = &He86
Const adErrIntegrityViolation = &He87
Const adErrPermissionDenied = &He88
Const adErrDataOverflow = &He89
Const adErrSchemaViolation = &He8a
Const adErrSignMismatch = &He8b
Const adErrCantConvertvalue = &He8c
Const adErrCantCreate = &He8d
Const adErrColumnNotOnThisRow = &He8e
Const adErrURLIntegrViolSetColumns = &He8f
Const adErrURLDoesNotExist = &He8f
Const adErrTreePermissionDenied = &He90
Const adErrInvalidURL = &He91
Const adErrResourceLocked = &He92
Const adErrResourceExists = &He93
Const adErrCannotComplete = &He94
Const adErrVolumeNotFound = &He95
Const adErrOutOfSpace = &He96
Const adErrResourceOutOfScope = &He97
Const adErrUnavailable = &He98
Const adErrURLNamedRowDoesNotExist = &He99
Const adErrDelResOutOfScope = &He9a
Const adErrPropInvalidColumn = &He9b
Const adErrPropInvalidOption = &He9c
Const adErrPropInvalidValue = &He9d
Const adErrPropConflicting = &He9e
Const adErrPropNotAllSettable = &He9f
Const adErrPropNotSet = &Hea0
Const adErrPropNotSettable = &Hea1
Const adErrPropNotSupported = &Hea2
Const adErrCatalogNotSet = &Hea3
Const adErrCantChangeConnection = &Hea4
Const adErrFieldsUpdateFailed = &Hea5
Const adErrDenyNotSupported = &Hea6
Const adErrDenyTypeNotSupported = &Hea7

'---- ParameterAttributesEnum Values ----
Const adParamSigned = &H0010
Const adParamNullable = &H0040
Const adParamLong = &H0080

'---- ParameterDirectionEnum Values ----
Const adParamUnknown = &H0000
Const adParamInput = &H0001
Const adParamOutput = &H0002
Const adParamInputOutput = &H0003
Const adParamReturnValue = &H0004

'---- CommandTypeEnum Values ----
Const adCmdUnknown = &H0008
Const adCmdText = &H0001
Const adCmdTable = &H0002
Const adCmdStoredProc = &H0004
Const adCmdFile = &H0100
Const adCmdTableDirect = &H0200

'---- EventStatusEnum Values ----
Const adStatusOK = &H0000001
Const adStatusErrorsOccurred = &H0000002
Const adStatusCantDeny = &H0000003
Const adStatusCancel = &H0000004
Const adStatusUnwantedEvent = &H0000005

'---- EventReasonEnum Values ----
Const adRsnAddNew = 1
Const adRsnDelete = 2
Const adRsnUpdate = 3
Const adRsnUndoUpdate = 4
Const adRsnUndoAddNew = 5
Const adRsnUndoDelete = 6
Const adRsnRequery = 7
Const adRsnResynch = 8
Const adRsnClose = 9
Const adRsnMove = 10
Const adRsnFirstChange = 11
Const adRsnMoveFirst = 12
Const adRsnMoveNext = 13
Const adRsnMovePrevious = 14
Const adRsnMoveLast = 15

'---- SchemaEnum Values ----
Const adSchemaProviderSpecific = -1
Const adSchemaAsserts = 0
Const adSchemaCatalogs = 1
Const adSchemaCharacterSets = 2
Const adSchemaCollations = 3
Const adSchemaColumns = 4
Const adSchemaCheckConstraints = 5
Const adSchemaConstraintColumnUsage = 6
Const adSchemaConstraintTableUsage = 7
Const adSchemaKeyColumnUsage = 8
Const adSchemaReferentialConstraints = 9
Const adSchemaTableConstraints = 10
Const adSchemaColumnsDomainUsage = 11
Const adSchemaIndexes = 12
Const adSchemaColumnPrivileges = 13
Const adSchemaTablePrivileges = 14
Const adSchemaUsagePrivileges = 15
Const adSchemaProcedures = 16
Const adSchemaSchemata = 17
Const adSchemaSQLLanguages = 18
Const adSchemaStatistics = 19
Const adSchemaTables = 20
Const adSchemaTranslations = 21
Const adSchemaProviderTypes = 22
Const adSchemaViews = 23
Const adSchemaViewColumnUsage = 24
Const adSchemaViewTableUsage = 25
Const adSchemaProcedureParameters = 26
Const adSchemaForeignKeys = 27
Const adSchemaPrimaryKeys = 28
Const adSchemaProcedureColumns = 29
Const adSchemaDBInfoKeywords = 30
Const adSchemaDBInfoLiterals = 31
Const adSchemaCubes = 32
Const adSchemaDimensions = 33
Const adSchemaHierarchies = 34
Const adSchemaLevels = 35
Const adSchemaMeasures = 36
Const adSchemaProperties = 37
Const adSchemaMembers = 38
Const adSchemaTrustees = 39

'---- FieldStatusEnum Values ----
Const adFieldOK = 0
Const adFieldCantConvertValue = 2
Const adFieldIsNull = 3
Const adFieldTruncated = 4
Const adFieldSignMismatch = 5
Const adFieldDataOverflow = 6
Const adFieldCantCreate = 7
Const adFieldUnavailable = 8
Const adFieldPermissionDenied = 9
Const adFieldIntegrityViolation = 10
Const adFieldSchemaViolation = 11
Const adFieldBadStatus = 12
Const adFieldDefault = 13
Const adFieldIgnore = 15
Const adFieldDoesNotExist = 16
Const adFieldInvalidURL = 17
Const adFieldResourceLocked = 18
Const adFieldResourceExists = 19
Const adFieldCannotComplete = 20
Const adFieldVolumeNotFound = 21
Const adFieldOutOfSpace = 22
Const adFieldCannotDeleteSource = 23
Const adFieldReadOnly = 24
Const adFieldResourceOutOfScope = 25
Const adFieldAlreadyExists = 26
Const adFieldPendingInsert = &H10000
Const adFieldPendingDelete = &H20000
Const adFieldPendingChange = &H40000
Const adFieldPendingUnknown = &H80000
Const adFieldPendingUnknownDelete = &H100000

'---- SeekEnum Values ----
Const adSeekFirstEQ = &H1
Const adSeekLastEQ = &H2
Const adSeekAfterEQ = &H4
Const adSeekAfter = &H8
Const adSeekBeforeEQ = &H10
Const adSeekBefore = &H20

'---- ADCPROP_UPDATECRITERIA_ENUM Values ----
Const adCriteriaKey = 0
Const adCriteriaAllCols = 1
Const adCriteriaUpdCols = 2
Const adCriteriaTimeStamp = 3

'---- ADCPROP_ASYNCTHREADPRIORITY_ENUM Values ----
Const adPriorityLowest = 1
Const adPriorityBelowNormal = 2
Const adPriorityNormal = 3
Const adPriorityAboveNormal = 4
Const adPriorityHighest = 5

'---- ADCPROP_AUTORECALC_ENUM Values ----
Const adRecalcUpFront = 0
Const adRecalcAlways = 1

'---- ADCPROP_UPDATERESYNC_ENUM Values ----

'---- ADCPROP_UPDATERESYNC_ENUM Values ----

'---- MoveRecordOptionsEnum Values ----
Const adMoveUnspecified = -1
Const adMoveOverWrite = 1
Const adMoveDontUpdateLinks = 2
Const adMoveAllowEmulation = 4

'---- CopyRecordOptionsEnum Values ----
Const adCopyUnspecified = -1
Const adCopyOverWrite = 1
Const adCopyAllowEmulation = 4
Const adCopyNonRecursive = 2

'---- StreamTypeEnum Values ----
Const adTypeBinary = 1
Const adTypeText = 2

'---- LineSeparatorEnum Values ----
Const adLF = 10
Const adCR = 13
Const adCRLF = -1

'---- StreamOpenOptionsEnum Values ----
Const adOpenStreamUnspecified = -1
Const adOpenStreamAsync = 1
Const adOpenStreamFromRecord = 4

'---- StreamWriteEnum Values ----
Const adWriteChar = 0
Const adWriteLine = 1

'---- SaveOptionsEnum Values ----
Const adSaveCreateNotExist = 1
Const adSaveCreateOverWrite = 2

'---- FieldEnum Values ----
Const adDefaultStream = -1
Const adRecordURL = -2

'---- StreamReadEnum Values ----
Const adReadAll = -1
Const adReadLine = -2

'---- RecordTypeEnum Values ----
Const adSimpleRecord = 0
Const adCollectionRecord = 1
Const adStructDoc = 2
*/
?>