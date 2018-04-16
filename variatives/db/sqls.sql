/**
Вибираємо рейтинги курсів, виставлені студентами.
Якщо студент рейтинг не задав, то використовується рейтинг,
виставлений у властивостях блока
*/
SELECT 
     CONCAT(var_block.id,'-',var_blockcourse.courseid,'-',cohort_members.userid) AS k,
     cohort_members.userid, var_block.id AS varblockid,
     IFNULL(var_userblockcourse.varuserblockcourserating, var_blockcourse.varblockcourserating) AS rating, 
     var_blockcourse.courseid,
     var_blockgroup.varblockgroupnumcourses,
     var_userblockcourse.varuserblockcourserating, var_blockcourse.varblockcourserating,
     var_block.varblockname,  
     var_blockcourse.varblockcoursegroup, course.fullname AS course_fullname
FROM mdl_cohort_members cohort_members 
     INNER JOIN mdl_var_group var_group ON var_group.cohortid=cohort_members.cohortid
     INNER JOIN mdl_var_block var_block 
           ON (    var_block.varformid=var_group.varformid 
               AND var_block.varlevelid=var_group.varlevelid
               AND var_block.vargroupyear=var_group.vargroupyear)
     INNER JOIN mdl_var_blockgroup var_blockgroup 
           ON (     var_blockgroup.varblockid=var_block.id
               AND (var_blockgroup.vardepartmentid=0 OR var_blockgroup.vardepartmentid=var_group.vardepartmentid)
               AND (var_blockgroup.varspecialityid=0 OR var_blockgroup.varspecialityid=var_group.varspecialityid)
               )
     INNER JOIN mdl_var_blockcourse var_blockcourse ON var_block.id=var_blockcourse.varblockid
     INNER JOIN mdl_course course ON course.id=var_blockcourse.courseid
     LEFT JOIN mdl_var_userblockcourse var_userblockcourse 
          ON(
             var_block.id=var_userblockcourse.varblockid
             AND course.id=var_userblockcourse.courseid
             AND var_userblockcourse.userid=cohort_members.userid
          )
WHERE var_block.id=9
ORDER BY cohort_members.userid,
         rating,
         var_block.varblockname,
         var_userblockcourse.varuserblockcourserating,
         var_blockcourse.varblockcourserating;




/**
Вибираємо запропоновані студентам курси

використовуємо у формі призначення курсу для одного студента
*/
SELECT CONCAT(var_block.id,'-',var_blockcourse.courseid,'-',cohort_members.userid) AS k,
     var_block.id AS varblockid, var_block.varblockname,
     var_blockcourse.courseid,var_blockcourse.varblockcoursegroup, course.fullname AS course_fullname,
     var_userblockcourse.varuserblockcourserating, var_blockcourse.varblockcourserating,
     IFNULL(var_userblockcourse.varuserblockcourserating, var_blockcourse.varblockcourserating) AS rating
FROM mdl_cohort_members cohort_members 
     INNER JOIN mdl_var_group var_group ON var_group.cohortid=cohort_members.cohortid
     INNER JOIN mdl_var_block var_block 
           ON (    var_block.varformid=var_group.varformid 
               AND var_block.varlevelid=var_group.varlevelid
               AND var_block.vargroupyear=var_group.vargroupyear)
     INNER JOIN mdl_var_blockgroup var_blockgroup 
           ON (     var_blockgroup.varblockid=var_block.id
               AND (var_blockgroup.vardepartmentid=0 OR var_blockgroup.vardepartmentid=var_group.vardepartmentid)
               AND (var_blockgroup.varspecialityid=0 OR var_blockgroup.varspecialityid=var_group.varspecialityid)
               )
     INNER JOIN mdl_var_blockcourse var_blockcourse ON var_block.id=var_blockcourse.varblockid
     INNER JOIN mdl_course course ON course.id=var_blockcourse.courseid
     LEFT JOIN mdl_var_userblockcourse var_userblockcourse 
          ON(
             var_block.id=var_userblockcourse.varblockid
             AND course.id=var_userblockcourse.courseid
          )
WHERE cohort_members.userid=10703
ORDER BY var_block.varblockname,
         var_userblockcourse.varuserblockcourserating,
         var_blockcourse.varblockcourserating;




/**
Перелік студентів, які не вибрали курси
*/
SELECT var_block.id AS varblockid, var_block.varblockname,`user`.id userid, `user`.lastname userlastname, `user`.firstname userfirstname,
var_group.id vargroupid, var_group.vargroupcode, var_group.vargroupyear,
var_group.vardepartmentid, var_department.vardepartmentname,
var_group.varformid, var_form.varformname,
var_group.varlevelid, var_level.varlevelname,
var_group.varspecialityid , var_speciality.varspecialityname
FROM mdl_user `user`
     INNER JOIN mdl_cohort_members cohort_members ON `user`.id=cohort_members.userid
     INNER JOIN mdl_var_group var_group ON var_group.cohortid=cohort_members.cohortid
     INNER JOIN mdl_var_department var_department ON var_department.id=var_group.vardepartmentid
     INNER JOIN mdl_var_form var_form ON var_form.id=var_group.varformid
     INNER JOIN mdl_var_level var_level ON var_level.id=var_group.varlevelid
     INNER JOIN mdl_var_speciality var_speciality ON var_group.varspecialityid=var_speciality.id
     INNER JOIN mdl_var_block var_block 
           ON (    var_block.varformid=var_group.varformid 
               AND var_block.varlevelid=var_group.varlevelid
               AND var_block.vargroupyear=var_group.vargroupyear)
     INNER JOIN mdl_var_blockgroup var_blockgroup 
           ON (     var_blockgroup.varblockid=var_block.id
               AND (var_blockgroup.vardepartmentid=0 OR var_blockgroup.vardepartmentid=var_department.id)
               AND (var_blockgroup.varspecialityid=0 OR var_blockgroup.varspecialityid=var_speciality.id)
               )

     INNER JOIN mdl_var_blockcourse var_blockcourse ON var_block.id=var_blockcourse.varblockid
     LEFT JOIN mdl_var_userblockcourse var_userblockcourse 
          ON(
             var_block.id=var_userblockcourse.varblockid
             AND var_blockcourse.courseid=var_userblockcourse.courseid
          )
WHERE var_userblockcourse.varuserblockcourserating IS NULL
GROUP BY userid, vargroupid
ORDER BY var_block.id, var_department.vardepartmentname, var_form.varformname, var_level.varlevelname , var_speciality.varspecialityname, userlastname;















/**
Вибираємо призначені студентам курси
*/

SELECT `user`.id userid, `user`.lastname userlastname, `user`.firstname userfirstname,
var_group.id vargroupid, var_group.vargroupcode, var_group.vargroupyear,
var_group.vardepartmentid, var_department.vardepartmentname,
var_group.varformid, var_form.varformname,
var_group.varlevelid, var_level.varlevelname,
var_group.varspecialityid , var_speciality.varspecialityname,
var_block.varblockname,
var_enroll.courseid, var_blockcourse.varblockcoursegroup ,course.fullname AS course_fullname
FROM mdl_user `user`
     INNER JOIN mdl_cohort_members cohort_members ON `user`.id=cohort_members.userid
     INNER JOIN mdl_var_group var_group ON var_group.cohortid=cohort_members.cohortid
     INNER JOIN mdl_var_department var_department ON var_department.id=var_group.vardepartmentid
     INNER JOIN mdl_var_form var_form ON var_form.id=var_group.varformid
     INNER JOIN mdl_var_level var_level ON var_level.id=var_group.varlevelid
     INNER JOIN mdl_var_speciality var_speciality ON var_group.varspecialityid=var_speciality.id
     INNER JOIN mdl_var_enroll var_enroll ON `user`.id=var_enroll.userid
     INNER JOIN mdl_course course ON course.id=var_enroll.courseid
     INNER JOIN mdl_var_block var_block ON var_block.id=var_enroll.varblockid
     INNER JOIN mdl_var_blockcourse var_blockcourse 
           ON (    var_blockcourse.varblockid=var_block.id
               AND var_blockcourse.courseid = course.id
           )
     
ORDER BY var_department.vardepartmentname, var_form.varformname, var_level.varlevelname , var_speciality.varspecialityname, userlastname
;



/**
Вибираємо запропоновані студентам курси

використовуємо для сторінки вибору курсів
для звіту про невибрані курси
для звіту про вибрані курси
у формі призначення курсу для студента
*/
SELECT `user`.id userid, `user`.lastname userlastname, `user`.firstname userfirstname,
var_group.id vargroupid, var_group.vargroupcode, var_group.vargroupyear,
var_group.vardepartmentid, var_department.vardepartmentname,
var_group.varformid, var_form.varformname,
var_group.varlevelid, var_level.varlevelname,
var_group.varspecialityid , var_speciality.varspecialityname,
var_blockcourse.courseid,var_blockcourse.varblockcoursegroup, course.fullname AS course_fullname,
var_userblockcourse.varuserblockcourserating, var_blockcourse.varblockcourserating
FROM mdl_user `user`
     INNER JOIN mdl_cohort_members cohort_members ON `user`.id=cohort_members.userid
     INNER JOIN mdl_var_group var_group ON var_group.cohortid=cohort_members.cohortid
     INNER JOIN mdl_var_department var_department ON var_department.id=var_group.vardepartmentid
     INNER JOIN mdl_var_form var_form ON var_form.id=var_group.varformid
     INNER JOIN mdl_var_level var_level ON var_level.id=var_group.varlevelid
     INNER JOIN mdl_var_speciality var_speciality ON var_group.varspecialityid=var_speciality.id
     INNER JOIN mdl_var_block var_block 
           ON (    var_block.varformid=var_group.varformid 
               AND var_block.varlevelid=var_group.varlevelid
               AND var_block.vargroupyear=var_group.vargroupyear)
     INNER JOIN mdl_var_blockgroup var_blockgroup 
           ON (     var_blockgroup.varblockid=var_block.id
               AND (var_blockgroup.vardepartmentid=0 OR var_blockgroup.vardepartmentid=var_department.id)
               AND (var_blockgroup.varspecialityid=0 OR var_blockgroup.varspecialityid=var_speciality.id)
               )

     INNER JOIN mdl_var_blockcourse var_blockcourse ON var_block.id=var_blockcourse.varblockid
     INNER JOIN mdl_course course ON course.id=var_blockcourse.courseid
     LEFT JOIN mdl_var_userblockcourse var_userblockcourse 
          ON(
             var_block.id=var_userblockcourse.varblockid
             AND course.id=var_userblockcourse.courseid
          )
     
ORDER BY var_department.vardepartmentname, var_form.varformname, var_level.varlevelname , var_speciality.varspecialityname, userlastname;






/**
Вибираємо студентів, які мають доступ до блоку
використовуємо для вибору
у формі призначення курсу для студента
*/
SELECT `user`.id userid, `user`.lastname userlastname, `user`.firstname userfirstname,
var_group.id vargroupid, var_group.vargroupcode, var_group.vargroupyear,
var_group.vardepartmentid, var_department.vardepartmentname,
var_group.varformid, var_form.varformname,
var_group.varlevelid, var_level.varlevelname,
var_group.varspecialityid , var_speciality.varspecialityname,
var_block.id AS varblockid
FROM mdl_user `user`
     INNER JOIN mdl_cohort_members cohort_members ON `user`.id=cohort_members.userid
     INNER JOIN mdl_var_group var_group ON var_group.cohortid=cohort_members.cohortid
     INNER JOIN mdl_var_department var_department ON var_department.id=var_group.vardepartmentid
     INNER JOIN mdl_var_form var_form ON var_form.id=var_group.varformid
     INNER JOIN mdl_var_level var_level ON var_level.id=var_group.varlevelid
     INNER JOIN mdl_var_speciality var_speciality ON var_group.varspecialityid=var_speciality.id
     INNER JOIN mdl_var_block var_block 
           ON (    var_block.varformid=var_group.varformid 
               AND var_block.varlevelid=var_group.varlevelid
               AND var_block.vargroupyear=var_group.vargroupyear)
     INNER JOIN mdl_var_blockgroup var_blockgroup 
           ON (     var_blockgroup.varblockid=var_block.id
               AND (var_blockgroup.vardepartmentid=0 OR var_blockgroup.vardepartmentid=var_department.id)
               AND (var_blockgroup.varspecialityid=0 OR var_blockgroup.varspecialityid=var_speciality.id)
               )
WHERE var_block.id=9
     
ORDER BY userlastname, var_department.vardepartmentname, var_form.varformname, 
         var_level.varlevelname , var_speciality.varspecialityname
;









/**
Вибираємо курси, приєднані до блоку
*/
SELECT 
   var_blockcourse.courseid,
   var_blockcourse.varblockcoursegroup,
   course.fullname AS course_fullname,
   var_blockcourse.varblockcourserating
FROM mdl_var_blockcourse var_blockcourse
     INNER JOIN mdl_course course ON course.id=var_blockcourse.courseid
ORDER BY varblockcourserating, course_fullname;




