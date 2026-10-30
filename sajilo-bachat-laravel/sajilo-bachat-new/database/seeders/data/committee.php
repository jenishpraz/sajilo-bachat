<?php

/*
 * Committee & sub-committee structure transcribed from the supplied PDF
 * ("समिति उपसमिति सदस्यहरुको विवरण"). The PDF uses a legacy Nepali font, so it was read
 * visually from the rendered pages. `no` is the serial number printed in the PDF
 * (conveners of sub-committees are listed there without a number, so they use null).
 * English names are phonetic transliterations — review them in Admin → Board Members.
 *
 * [ref, section, name_ne, name_en, designation_ne, designation_en]
 */

$m = ['सदस्य', 'Member'];

return [
    // ---- Advisors
    ['1',  'advisors', 'माईला प्रजापति', 'Maila Prajapati', 'सल्लाहकार', 'Advisor'],
    ['2',  'advisors', 'बाबु कृष्ण श्रेष्ठ', 'Babu Krishna Shrestha', 'कानुनी सल्लाहकार', 'Legal Advisor'],
    ['3',  'advisors', 'बाबु नाथ प्रजापति', 'Babu Nath Prajapati', 'सल्लाहकार', 'Advisor'],
    ['4',  'advisors', 'तुल्सी हरि प्रजापति', 'Tulsi Hari Prajapati', 'सल्लाहकार', 'Advisor'],

    // ---- Board of Directors
    ['5',  'board', 'बालमुकुन्द प्रजापति', 'Balmukunda Prajapati', 'संचालक समिति अध्यक्ष', 'Chairperson'],
    ['6',  'board', 'अजय प्रजापति', 'Ajay Prajapati', 'उपाध्यक्ष', 'Vice-Chairperson'],
    ['7',  'board', 'रीता लक्ष्मी जडाली', 'Rita Laxmi Jadali', 'सचिव', 'Secretary'],
    ['8',  'board', 'रजनी प्रजापति', 'Rajani Prajapati', 'कोषाध्यक्ष', 'Treasurer'],
    ['9',  'board', 'उत्तम प्रजापति', 'Uttam Prajapati', ...$m],
    ['10', 'board', 'लक्ष्मी प्रजापति', 'Laxmi Prajapati', ...$m],
    ['11', 'board', 'रत्न केशरी प्रजापति', 'Ratna Keshari Prajapati', ...$m],

    // ---- Accounts committee
    ['12', 'accounts', 'गंगा प्रजापति', 'Ganga Prajapati', 'लेखा समिति संयोजक', 'Convener, Accounts Committee'],
    ['13', 'accounts', 'बलराम प्रजापति', 'Balram Prajapati', ...$m],
    ['14', 'accounts', 'अशोक प्रजापति', 'Ashok Prajapati', ...$m],

    // ---- Loan sub-committee
    ['conv-loan', 'loan', 'अजय प्रजापति', 'Ajay Prajapati', 'संयोजक', 'Convener'],
    ['15', 'loan', 'राजन प्रजापति', 'Rajan Prajapati', 'ऋण उपसमिति सदस्य', 'Member, Loan Sub-committee'],
    ['16', 'loan', 'दिलकृष्ण प्रजापति', 'Dilkrishna Prajapati', ...$m],

    // ---- Women sub-committee
    ['conv-women', 'women', 'रिता लक्ष्मी जडाली', 'Rita Laxmi Jadali', 'महिला संयोजक', 'Women Convener'],
    ['19', 'women', 'सरु प्रजापति', 'Saru Prajapati', ...$m],
    ['20', 'women', 'सुर्य लक्ष्मी प्रजापति', 'Surya Laxmi Prajapati', ...$m],
    ['21', 'women', 'लक्ष्मी प्रजापति', 'Laxmi Prajapati', ...$m],
    ['22', 'women', 'निर्जला श्रेष्ठ', 'Nirjala Shrestha', ...$m],

    // ---- Expansion sub-committee
    ['conv-expansion', 'expansion', 'रजनी प्रजापति', 'Rajani Prajapati', 'संयोजक', 'Convener'],
    ['23', 'expansion', 'राम कृष्ण चकधर', 'Ram Krishna Chakradhar', 'सदस्य, बिस्तार उपसमिति', 'Member, Expansion Sub-committee'],
    ['24', 'expansion', 'चन्द्रमान श्रेष्ठ', 'Chandraman Shrestha', ...$m],
    ['25', 'expansion', 'रमेश जोशी श्रेष्ठ', 'Ramesh Joshi Shrestha', ...$m],
    ['26', 'expansion', 'हरि कुटु', 'Hari Kutu', ...$m],

    // ---- Education sub-committee
    ['conv-education', 'education', 'लक्ष्मी प्रजापति', 'Laxmi Prajapati', 'संयोजक', 'Convener'],
    ['27', 'education', 'कर्ण बहादुर प्रजापति', 'Karna Bahadur Prajapati', 'शिक्षा उपसमिति', 'Education Sub-committee'],
    ['28', 'education', 'रत्न कृष्ण प्रजापति', 'Ratna Krishna Prajapati', ...$m],
    ['29', 'education', 'अभिलाषा श्रेष्ठ', 'Abhilasha Shrestha', ...$m],
    ['30', 'education', 'सुजन प्याठ', 'Sujan Pyath', ...$m],

    // ---- Publicity sub-committee
    ['conv-publicity', 'publicity', 'रत्न केशरी प्रजापति', 'Ratna Keshari Prajapati', 'संयोजक', 'Convener'],
    ['31', 'publicity', 'नारायण देवी प्रजापति', 'Narayan Devi Prajapati', 'प्रचार प्रसार उपसमिति', 'Publicity Sub-committee'],
    ['32', 'publicity', 'जगत कृष्ण श्रेष्ठ', 'Jagat Krishna Shrestha', ...$m],
    ['33', 'publicity', 'चुनाब काजी बाडे', 'Chunab Kaji Bade', ...$m],
    ['34', 'publicity', 'दिपक मानान्धर', 'Deepak Manandhar', null, null],   // no designation printed in the PDF

    // ---- Youth sub-committee
    ['conv-youth', 'youth', 'उत्तम श्रेष्ठ', 'Uttam Shrestha', 'संयोजक', 'Convener'],
    ['35', 'youth', 'अनु गिरी', 'Anu Giri', 'युवा उपसमिति सदस्य', 'Member, Youth Sub-committee'],
    ['36', 'youth', 'रबिन्द्र कुमार पुडासैनी', 'Rabindra Kumar Pudasaini', ...$m],
    ['37', 'youth', 'सुबिन प्रजापति', 'Subin Prajapati', ...$m],
    ['38', 'youth', 'राजेन्द्र रंजित', 'Rajendra Ranjit', ...$m],
];
