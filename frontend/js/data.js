/* StudyFlow demo data. Local storage is used only for the Milestone 1 static prototype. */
window.SF_SEED={courses:[
{id:1,name:'Web Programming',code:'IT-204',instructor:'Dr. Amira Hadžić',semester:'Fall 2026',color:'blue',progress:72},
{id:2,name:'Database Systems',code:'IT-210',instructor:'Prof. Marko Kovač',semester:'Fall 2026',color:'purple',progress:58},
{id:3,name:'Software Engineering',code:'IT-220',instructor:'Dr. Lejla Sarić',semester:'Fall 2026',color:'green',progress:84},
{id:4,name:'Computer Networks',code:'IT-230',instructor:'Prof. Emir Babić',semester:'Fall 2026',color:'orange',progress:43}],
assignments:[
{id:1,title:'Build responsive portfolio',courseId:1,due:'2026-11-25',priority:'High',status:'In progress',category:'Project'},
{id:2,title:'SQL normalization exercise',courseId:2,due:'2026-11-24',priority:'Medium',status:'Pending',category:'Homework'},
{id:3,title:'UML use-case diagram',courseId:3,due:'2026-11-28',priority:'High',status:'Pending',category:'Project'},
{id:4,title:'Subnetting practice',courseId:4,due:'2026-11-20',priority:'Low',status:'Completed',category:'Homework'},
{id:5,title:'JavaScript quiz preparation',courseId:1,due:'2026-11-27',priority:'Medium',status:'In progress',category:'Exam'}],
sessions:[{id:1,title:'JavaScript practice',courseId:1,date:'2026-11-22',start:'09:00',end:'10:30',duration:90},{id:2,title:'Database revision',courseId:2,date:'2026-11-23',start:'14:00',end:'15:00',duration:60}],
notes:[{id:1,title:'HTTP request methods',body:'GET retrieves a resource. POST creates a resource. PUT replaces a resource. PATCH partially updates a resource. DELETE removes a resource.',courseId:1,category:'Revision',updated:'2026-11-21'},{id:2,title:'Database normal forms',body:'1NF: atomic values. 2NF: no partial dependency on a candidate key. 3NF: no transitive dependency on a candidate key.',courseId:2,category:'Lecture',updated:'2026-11-19'}],
categories:[{id:1,name:'Homework',color:'#5470e8'},{id:2,name:'Project',color:'#8a68df'},{id:3,name:'Exam',color:'#d68b36'},{id:4,name:'Revision',color:'#229879'}]};
