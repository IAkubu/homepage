-- CPSC 332 database schema
-- Includes commands to populate tables

-- Professors Table
CREATE TABLE Professors (
    SSN CHAR(11) PRIMARY KEY,
    Name VARCHAR(100),
    Street VARCHAR(100),
    City VARCHAR(50),
    State CHAR(2),
    Zip CHAR(5),
    AreaCode CHAR(3),
    Telephone CHAR(7),
    Sex CHAR(1),
    Title VARCHAR(50),
    Salary DECIMAL(10,2),
    Degrees VARCHAR(255)
);

-- Departments Table
CREATE TABLE Departments (
    DeptID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100),
    Telephone CHAR(10),
    OfficeLocation VARCHAR(100),
    Chairperson CHAR(11),
    FOREIGN KEY (Chairperson) REFERENCES Professors(SSN)
);

-- Courses Table
CREATE TABLE Courses (
    CourseID INT AUTO_INCREMENT PRIMARY KEY,
    Title VARCHAR(100),
    Textbook VARCHAR(100),
    Units INT,
    DeptID INT,
    PrerequisiteID INT,
    FOREIGN KEY (DeptID) REFERENCES Departments(DeptID),
    FOREIGN KEY (PrerequisiteID) REFERENCES Courses(CourseID)
);

-- Sections Table
CREATE TABLE Sections (
    SectionID INT AUTO_INCREMENT PRIMARY KEY,
    CourseID INT,
    SectionNumber INT,
    Classroom VARCHAR(50),
    Seats INT,
    MeetingDays VARCHAR(50),
    StartTime TIME,
    EndTime TIME,
    ProfessorID CHAR(11),
    FOREIGN KEY (CourseID) REFERENCES Courses(CourseID),
    FOREIGN KEY (ProfessorID) REFERENCES Professors(SSN)
);

-- Students Table
CREATE TABLE Students (
    CampusID CHAR(9) PRIMARY KEY,
    FirstName VARCHAR(50),
    LastName VARCHAR(50),
    Street VARCHAR(100),
    City VARCHAR(50),
    State CHAR(2),
    Zip CHAR(5),
    AreaCode CHAR(3),
    Telephone CHAR(7),
    MajorDeptID INT,
    FOREIGN KEY (MajorDeptID) REFERENCES Departments(DeptID)
);

-- Minors Table
CREATE TABLE Minors (
    StudentID CHAR(9),
    MinorDeptID INT,
    PRIMARY KEY (StudentID, MinorDeptID),
    FOREIGN KEY (StudentID) REFERENCES Students(CampusID),
    FOREIGN KEY (MinorDeptID) REFERENCES Departments(DeptID)
);

-- Enrollment Table
CREATE TABLE Enrollment (
    StudentID CHAR(9),
    SectionID INT,
    Grade VARCHAR(2),
    PRIMARY KEY (StudentID, SectionID),
    FOREIGN KEY (StudentID) REFERENCES Students(CampusID),
    FOREIGN KEY (SectionID) REFERENCES Sections(SectionID)
);

-- Insert data into the Departments table
INSERT INTO Departments (DeptID, Name, Telephone, OfficeLocation, Chairperson)
VALUES
    (1, 'Computer Science', '555-9876', 'Building 1, Room 101', '123-45-6789'),
    (2, 'Mathematics', '555-6543', 'Building 2, Room 202', '987-65-4321');

-- Insert data into the Courses table
INSERT INTO Courses (CourseID, Title, Textbook, Units, DeptID, PrerequisiteID)
VALUES
    (1, 'Introduction to Programming', 'Introduction to Programming with Python', 3, 1, NULL),
    (2, 'Data Structures', 'Algorithms, Part I', 4, 1, 1),
    (3, 'Calculus I', 'Calculus: Early Transcendentals', 4, 2, NULL),
    (4, 'Linear Algebra', 'Linear Algebra and Its Applications', 3, 2, 2);

-- Insert data into the Enrollment table
INSERT INTO Enrollment (StudentID, SectionID, Grade)
VALUES
    (1, 1, 'A'),
    (1, 2, 'B+'),
    (1, 3, 'B+'),
    (2, 1, 'A-'),
    (2, 3, 'B'),
    (2, 6, 'A'),
    (3, 2, 'A'),
    (3, 4, 'C+'),
    (3, 5, 'A'),
    (4, 3, 'B+'),
    (4, 5, 'B-'),
    (4, 6, 'A'),
    (5, 1, 'A'),
    (5, 3, 'C'),
    (6, 2, 'B'),
    (6, 5, 'A-'),
    (7, 3, 'B+'),
    (7, 4, 'C'),
    (8, 1, 'A-'),
    (8, 6, 'B');


-- Insert data into the Professors table
INSERT INTO Professors (SSN, Name, Street, City, State, Zip, AreaCode, Telephone, Sex, Title, Salary, Degrees)
VALUES
    ('111-22-3333', 'Dr. Mike Brown', '321 Pine St', 'Fullerton', 'CA', '92831', '714', '5553456', 'M', 'Assistant Professor', 60000.00, 'PhD'),
    ('123-45-6789', 'Dr. John Smith', '456 Oak St', 'Fullerton', 'CA', '92831', '714', '5556789', 'M', 'Professor', 80000.00, 'PhD'),
    ('987-65-4321', 'Dr. Jane Doe', '789 Maple St', 'Fullerton', 'CA', '92831', '714', '5552345', 'F', 'Associate Professor', 70000.00, 'PhD');

-- Insert data into the Sections table
INSERT INTO Sections (SectionID, CourseID, SectionNumber, Classroom, Seats, MeetingDays, StartTime, EndTime, ProfessorID)
VALUES
    (1, 1, 1, 'Room 101', 30, 'MWF', '09:00:00', '10:15:00', '123-45-6789'),
    (2, 1, 2, 'Room 102', 30, 'TTh', '10:30:00', '11:45:00', '987-65-4321'),
    (3, 2, 1, 'Room 201', 25, 'MWF', '11:00:00', '12:15:00', '111-22-3333'),
    (4, 2, 2, 'Room 202', 25, 'TTh', '12:30:00', '13:45:00', '123-45-6789'),
    (5, 3, 1, 'Room 301', 40, 'MWF', '08:00:00', '09:15:00', '987-65-4321'),
    (6, 4, 1, 'Room 401', 35, 'TTh', '14:00:00', '15:15:00', '111-22-3333');
-- Insert data into the Students table
INSERT INTO Students (CampusID, FirstName, LastName, Street, City, State, Zip, AreaCode, Telephone, MajorDeptID)
VALUES
    (1, 'John', 'Doe', '123 Main St', 'Fullerton', 'CA', '92831', '714', '5551234', 1),
    (2, 'Alice', 'Johnson', '456 Oak St', 'Fullerton', 'CA', '92831', '714', '5552345', 1),
    (3, 'Bob', 'Smith', '789 Pine St', 'Fullerton', 'CA', '92831', '714', '5553456', 1),
    (4, 'Emily', 'Davis', '321 Birch St', 'Fullerton', 'CA', '92831', '714', '5554567', 1),
    (5, 'Michael', 'Brown', '654 Cedar St', 'Fullerton', 'CA', '92831', '714', '5555678', 2),
    (6, 'Sarah', 'Wilson', '987 Elm St', 'Fullerton', 'CA', '92831', '714', '5556789', 2),
    (7, 'David', 'Taylor', '123 Oak St', 'Fullerton', 'CA', '92831', '714', '5557890', 2),
    (8, 'Laura', 'Martinez', '456 Maple St', 'Fullerton', 'CA', '92831', '714', '5558901', 2);
