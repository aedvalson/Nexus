<? 
header("Content-type: text/css");
include "./findconfig.php";

?>

* {
	margin: 0;
}

.fakecaps
{
font-size: smaller;
text-transform: uppercase;
}


.truecaps
{
font-variant: small-caps
}

html, body
{
	height: 100%;
	padding: 0px;
	margin: 0px;
	font-size: 1em;

}

input
{
	outline: none;
	}

input.button
{
	padding:2px;
	}

select
{
	margin-top: 1px;
	padding: 1px;
	}
.label
{
	clear:both;
	float:left;
	font-weight:bold;
	margin-left:15px;
	margin-top: 5px;
	margin-bottom: 5px;
	width: 120px;
	}

.value
{
	float:left;
	margin-top:5px;
	margin-bottom: 5px;
	margin-left:15px;
	}

table.report
{
	text-align: center;
	font-family: Verdana;
	font-weight: normal;
	font-size: 11px;
	color: #404040;
	background-color: #fafafa;
	/*border: 1px #6699CC solid; */
	border-collapse: collapse;
	margin-bottom:1em;
	border: 2px solid silver;
	font-size: 2em;
}
table.report td
{ 
	text-align: left;
	font-family: Verdana, sans-serif, Arial;
	font-weight: normal;
	font-size: 11px;
	color: #404040;
	vertical-align:top;
		border-collapse: collapse;
	border: 2px solid silver;
	font-size: 0.5em;
 }

 table.report td.labelCell
 {
	font-weight: bold;
	}

table.report tr td
{
	background-color: #f1f1f1;
	padding: 6px 10px 6px 10px;
}




table.sectionIndexTable
	{
	margin-left:auto;
	margin-right: auto;
	width: 880px;
	font-family:'Arial Black';
	font-size: 115%;
	border: 2px solid silver;
	margin-top: 20px;
	margin-bottom: 200px;
	}

table.sectionIndexTable > thead th
	{
	background-color:#CAE8EA;
	color: #444;
	letter-spacing: 2px;
	text-align: left;
	padding: 6px 12px 6px 14px;
	}

table.sectionIndexTable tbody
	{
	font-size: 90%;
	color: #444;
	}

table.sectionIndexTable td
	{
	padding:5px;
	margin:0px;
	}

table.sectionIndexTable td.iconCell
	{
	width: 80px;
	padding: 6px;
	text-align: center;
	}

table.sectionIndexTable td.linksCell
	{
	padding-left: 6px;
	letter-spacing: 1px;
	}

table.sectionIndexTable td.countCell
	{
	padding-left: 60px;
	}

table.sectionIndexTable td.dateCell
	{
	padding-right: 12px;
	text-align: right;
	font-size: 90%;
	}

table.sectionIndexTable td.iconCell img
	{
		width: 56px;
		}

table.sectionIndexTable tr td
	{
	background-color: #fff;
	}


table.sectionIndexTable tr.odd td
	{
	background-color:  #f1f1f1;
	}




table.data thead tr th, table.tablesorter tfoot tr th {
}
table.data thead tr .header {
	cursor: pointer;
}

table.data select
{
	width: 100%;
	}


table.data thead tr .headerSortUp {
	background-position: center right;
	background-image: url(/<?= $ROOTPATH ?>/images/asc.gif);
}
table.data thead tr .headerSortDown {
	
	background-position: center right;
	background-image: url(/<?= $ROOTPATH ?>/images/desc.gif);
}
table.data
{ text-align: center;
font-family: Verdana;
font-weight: normal;
font-size: 11px;
color: #404040;
width: 100%;
background-color: #fafafa;
/*border: 1px #6699CC solid; */
border-collapse: collapse;
border-spacing: 0px;
border-bottom: 1px solid #C1DAD7;
border-left: 1px solid #C1DAD7;
border-right: 1px solid #C1DAD7;
margin-bottom:1em;
}

table.data .pagedisplay
{
background-color:#F1F1F1;
border:0 none;
font-size:10pt;
text-align:center;
vertical-align:top;
width:50px;
text-align: center;
}



h3.tableHeadline
{
	padding:0px;
	margin:0px;
	}

table.data th
{ 	border-right: 1px solid #C1DAD7;
	border-bottom: 1px solid #C1DAD7;
	border-top: 1px solid #C1DAD7;
	letter-spacing: 1px;
	text-transform: uppercase;
	text-align: left;
	padding: 6px 6px 6px 12px;
	background: #CAE8EA url(/<?= $ROOTPATH ?>/images/bg_header.jpg) no-repeat;
font: bold 11px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	color: #4f6b72; }

table.data td
{ /*border-bottom: 1px solid #9CF; */
border-top: 0px;
/*border-left: 1px solid #9CF; */
/*border-right: 0px; */
text-align: left;

font-family: Verdana, sans-serif, Arial;
font-weight: normal;
font-size: 11px;
color: #404040;
vertical-align:top;
border-right: 1px solid #C1DAD7;
 }

table.data tr td
{
	background-color: #f1f1f1;
	padding: 6px 10px 6px 10px;
}

table.data tr.odd td
{
	background-color: #fff;
	padding: 6px 10px 6px 10px;
	}



table.data tr.filterRow td
{
	background-color: #d1e0e2;
	vertical-align: top;
	padding:10px;
}
table.data tr.filterRow td > input, .editCell > input
{
	width: 90%;
	}


table.data tr.hoverRow td
{
	background-color: #b1cAc7;
}

table.data tr.editSelected td
{
	font-weight:bold;
	background-color: gray;
	}

table.data tbody tr:hover td
{
	background-color: #b1cAc7;
	}


table.data tr.edit
{
	border:2px solid silver;
	}

table.data tbody tr.edit td, table.data tbody tr.edit td:hover, table.data tbody tr.edit
{
	background-color:  #CAE8EA;
	}

table.data tr.edit td:hover
{
	background-color:  #CAE8EA;
	}

.mask
{
	margin:0px;
	padding:0px;
	width:100%;
	height:100%;
	}

.maskEdit
{
	margin:0px;
	padding:0px;
	width:100%;
	height:100%;
	color: red;
	display:none;
	}

.maskContainer
{
	padding:0px;
	}


.newspaper-a
{
	font-family:"Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
	font-size:12px;
	width:480px;
	text-align:left;
	border-collapse:collapse;
	border:1px solid #69c;
	margin:20px;
}
.newspaper-a th
{
	font-weight:normal;
	font-size:14px;
	color:#039;
	border-bottom:1px dashed #69c;
	padding:12px 17px;

}
.newspaper-a td
{
	color:#669;
	padding:7px 17px;
}
.newspaper-a tbody tr:hover td
{
	color:#339;
	background:#d0dafd;
}





label {display:block;margin:2px;}
label {display:block;margin:2px;}
.form {padding:0px;margin:0px;background-color:#EDECDC;}
.form li {width:190px;margin:3px;padding:5px 5px 5px 30px;list-style:none;position:relative;}
*html .form li {left:-15px;}
.form li img {position:absolute;left:5px;}
.form .error {border:1px solid #A90000;padding:4px 4px 4px 29px;background-color:#F8E5E5;}
.form .success {border:1px solid #74F019;padding:4px 4px 4px 29px;background-color:#DEF8CA;}
.form .selected {border:1px solid #1AA8E1;padding:4px 4px 4px 29px;background-color:#8DD8F7;}
#login_table .pad {padding:15px;}
.form input.login {padding:2px 7px;width:auto;}
.form input {width:180px;}





.spacer
{
	clear:both;
	height:1px;
	}

.divHeadBackground
{
	background-color: #030357;
	margin:0px;
}
.divFilters
{
	float:left;
	width: 130px;
	margin-left: 20px;
	margin-right: auto;
}

.divFilters > div
{
	padding-bottom: 10px;
	border-bottom:1px dotted gray;

	margin-bottom: 5px;
}


.divFilters > div > select
{
	margin-left:5px;
	}

.divTable
{
	float:left;
	width: 759px;
}	
.divHeadline
{
	background-color: #030357;
	width: 960px;
	margin-left:auto;
	margin-right:auto;
}
.divHeadTopBar
{
	background-color: #365181;
	width: 960px;
	height: 12px;
	margin-left:auto;
	margin-right:auto;
}
.divHeadNavBar
{
	background-color: #365181;
	width: 960px;
	height: 30px;
	margin-left:auto;
	margin-right:auto;
}

.divHeadNavBar a
{
	float:left;
	color: #f0f0f1;
	font-family: 'Arial Black';
	text-decoration: none;
	margin-left: 10px;
	padding-top:3px;
	outline: 0px;
	height:27px;
}

.divHeadNavBar a:hover
{
	color: #e1c7b3;
	
}
.divHeadNavBar :hover
{
	background-color: #fff;
}

div.detailsRow
{
	padding:2px;
	margin-left: auto;
	margin-right:auto;
	width:80%;
	border:1px solid silver;
	}

.formOptionSet
{
	display:none;
}



.contentBody
{
	background-image:url('/<?= $ROOTPATH ?>/images/bodyBG.png');
	background-repeat:repeat-x;
	background-color:#f8f8f6;
	width: 960px;
	margin-left:auto;
	margin-right:auto;
	margin-top:0px;
	margin-bottom:0px;
	padding-top:15px;
/*	min-height: 500px; */
	height:auto !important;
	min-height:80%;
}

#divFooter
{
 
	height:110px;
	background-color: #030357;
	margin:0px;
	clear:both;
	width:100%;
	position:relative;
	
	}
#divFooterContent
{
	position: relative;
	padding-top:26px;
	background-color: #365181;
	width: 960px;
	margin-left:auto;
	margin-right:auto;
	height:84px;
	color: #ccc;
	}
.horizontal
{
	list-style: none;
	padding: 0;
	margin: 0px 0px 0px 2em;

}
.horizontal li
{
	height: 30px;
	float: left;
	margin: 0 0.15em;
	padding-right: 3em;
} 
	


.navMenu
{
	float:left;
	width:170px;
	height:100%;
	}
.navHeaderdiv
{
	border-bottom: 1px solid gray;
	border-right: 1px solid gray;
	height: 2em;
	}
.contentHeaderDiv
{
	border-bottom: 1px solid gray;
	height: 2em;
	margin: 0px;
	padding:0px 15px 0px 0px;
}
.contentHeaderDiv > a
{
	float: right;
	border: 1px solid black;
	padding:2px;
	margin-left:5px;
	}

.contentDiv
{
	padding:15px;
	}
.navBullet
{
	border-bottom: 1px solid gray;
	border-right: 2px solid gray;
	height: 2em;
	background-color: #7690bf;
	color: #f8f8f8;
	}

.navContent
{
	border-right: 1px solid gray;
	background-color: transparent;
	width:169px;
	}
.navSpacer
{
	
	border-right: 1px solid gray;

	height: 100%;
	}


.navBullet a
{
	color: #f0f0f1;
	font-family: 'Arial Black';
	text-decoration: none;
	padding-left: 1.5em;
	outline: 0px;
	font-size: 0.9em;
	height: 2em;
	line-height: 2em;
}

div.navBulletSelected
{
	border-right: 0;
	background-color: transparent;
	border-top: 1px solid gray;
	border-bottom: 3px solid gray;
	border-left: 1px solid gray;
}
div.navBulletSelected a
{
	color: #030357;
	}

div.navBulletBorderTop
{
	border-right: 2px solid gray;
	height: 0.3em;
	background-color: #7690bf;
	border-bottom: 1px solid gray;
	color: #f8f8f8;
	}
div.navBulletBorderBottom
{
	border-right: 2px solid gray;
	height: 0.5em;
	background-color: #7690bf;
	color: #f8f8f8;
	}

div.navPageSpacing
{
	height: 100%;
	margin: auto;
	border-right: 1px solid gray;
	}

div.formDiv
{
	display:none;
	width:700px;
	margin:0px auto 20px auto;
	background-color: #eeefef;
	border: 1px silver dotted;
	}

div.formDiv > h1
{
	width: 695px;
	font-size: 1.2em;
	background-color: #365181;
	padding: 2px 0px 2px 5px;
	margin:0px;
	color: White;
	}

div.formBoxDialog
{
	display:none;
	width:670px;
	margin:15px auto 20px auto;
	border:1px silver dotted;
	}
div.formBoxDialog > h1
{
	padding: 2px 0px 2px 5px;
	font-size: 1.1em;
	color: #365181;
	margin:0px;
	width:665px;
	background-color: silver;
	}



div.commandBox
{
	clear:both;
	width:670px;
	margin:15px auto 20px auto;
	border:1px silver dotted;
	}

div.commandBox > h1
{
	padding: 2px 0px 2px 5px;
	font-size: 1.1em;
	color: #365181;
	margin:0px;
	width:665px;
	background-color: silver;
	}

div.commandBox > p
{
	margin:4px 0px 4px 15px;
	font-size: 0.9em;

	}
.navMenu h1, .contentHeaderDiv h1
{
	color: #b1662f;
	font-size: 0.9em;
	font-family: 'Arial Black';
	margin-left: 1.3em;
	padding:0px;
	margin-top:0px;
	}

.pageContent
{
	float:left;
	width:789px;
	min-height:350px;
	}


.Visible
	{
	display: block;
	}

a.linkList
{
	float:left;
	margin-right: 10px;
	}


#ui-datepicker-div
{
	display: none;
	}

.editCell
{
	display: none;
	}

.editCell input
{
	font-size: 11px;
	}

.editCell select
{
	font-size: 11px;

	}

.left{
text-align:left;
float:left;
}
.right{
float:right;
text-align:right;
}
.centered{
text-align:center;
}
