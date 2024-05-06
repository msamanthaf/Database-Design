
# Database Schema
The Schema is shown here as reference and helps the team making SQL queries.

#### Notification Table
- Notification(<ins>NotificationID</ins>: Char(50), Text: Char(255), Date: TimeStamp)
- *CK: NotificationID*
- *Date: not null*

#### User Table
- User(<ins>UserID</ins>: Char(50), FirstName: Char(50), LastName: Char(50), Password: Char(50), Email: Char(50))
- *CK: UserID*
- *Email: not null and unique*
- *Password: not null*

#### Project Manager Table
- Project Manager(<ins>**UserID**</ins>: Char(50), PermissionID: Char(50))
- *CK: UserID*
- *PermissionID: not null*

#### Member Table
- Member(<ins>**UserID**</ins>: Char(50), Role: Char(50))
- *CK: UserID*

#### Collaboration Request Table
- Collaboration Request(<ins>RequestID</ins>: Char(50), **UserID**: Char(50), **ProjectID**: Char(50), Status: Boolean)
- *CK: RequestID*
- *UserID: not null*
- *ProjectID: not null*

#### Project Table
- Project(<ins>ProjectID</ins>: Char(50), ProjectName: Char(50), Description: Char(255), CreateDate: TimeStamp, Status: Char(25))
- *CK: ProjectID*
- *ProjectName: not null and unique*
- *CreateDate: not null*

#### Task Table
- Task(<ins>TaskID</ins>: Char(50), TaskName: Char(50), **ProjectID**: Char(50), DueDate: TimeStamp, Statue: Boolean, Description: Char(255), CreateTime: TimeStamp, **SubTaskID**: Char(50)) 
- *CK: TaskID*
- *TaskName: not null*
- *ProjectID: not null*

#### Meetup Event Table
- Meetup Event(<ins>MeetupEventID</ins>: Char(50), **ProjectID**: Char(50), Location: Char(50), Time: TimeStamp, **UserID**: Char(50), **PermissionID**: Char(50))
- *CK: MeetingEventID*
- *ProjectID: not null*
- *UserID: not null*
- *PermissionID: not null*

#### Poll Table
- Poll(<ins>PollID</ins>: Char(50), **ProjectID**: Char(50), Topic: Char(255), Option A: Char(50), Option B: Char(50), Option C: Char(50), Option D: Char(50))
- *CK: PolIID*
- *ProjectID: not null*
- *Topic: not null*

#### Vote_Has Table
- Vote_Has(<ins>VoteID</ins>: Char(50), <ins>**PollID**</ins>: Char(50), Option: Char(50))
- *CK: the combination of VoteID and PollID*
- *Option: not null*

#### Task Comment_Contains Table
- Task Comment_Contains(<ins>CommentID</ins>: Char(50), <ins>**TaskID**</ins>: Char(50), Date: TimeStamp, Text: Char(255), **UserID**: Char(50))
- *CK: the combination of CommentID and TaskID*
- *UserID: not null*

#### Receives Table
- Receives(<ins>**NotificationID**</ins>: Char(50), <ins>**UserID**</ins>: Char(50))
- *CK: the combination of NotificationID and UserID*

#### Acquires Table
- Acquires(<ins>**UserID**</ins>: Char(50), <ins>**RequestID**</ins>: Char(50))
- *CK: the combination UserID and RequestID*

#### WorksOn Table
- WorksOn(<ins>**ProjectID**</ins>: Char(50), <ins>**UserID**</ins>: Char(50))
- *CK: the combination of ProjectID and UserID*

#### Makes Table
- Makes(<ins>**UserID**</ins>: Char(50), <ins>**VoteID**</ins>: Char(50))
- *CK: the combination of UserID and VoteID*