CREATE TABLE Account (
	id			int PRIMARY KEY AUTO_INCREMENT,
	email		varchar(50) UNIQUE NOT NULL,
    password	varchar(255) NOT NULL,
    name		varchar(50) NOT NULL -- first name
);

-- option user details to configure --
CREATE TABLE UserDetails (
	userId		int PRIMARY KEY,
	dob			date,
    lastName	varchar(50),
    heightFeet	float, -- probably have to convert feet and inches fields
    
    -- could be compared against reports --
    startWeight		float,
    
    -- misc descriptive fields about user conditions that could be shown when viewing account --
    allergies		varchar(200),
    conditions		varchar(500),
    medicalHistory  varchar(1000),
    
    FOREIGN KEY (userId) REFERENCES Account(id)
);

CREATE TABLE Reminder (
	userId			int NOT NULL,
    description		varchar(100),
    dueDate			datetime,
    FOREIGN KEY (userId) REFERENCES Account(id)
);

CREATE TABLE ExerciseReport (
	userId 			int NOT NULL,
    category		ENUM ('sedentary', 'light', 'moderate', 'active', 'extra_active')  NOT NULL,
    duration		time NOT NULL,
    currWeight      float NOT NULL,
    logDate			date NOT NULL,
	FOREIGN KEY (userId) REFERENCES Account(id)
);

CREATE TABLE MealReport (
	userId			int NOT NULL,
    logDate			date NOT NULL,
    mealType        ENUM ('breakfast', 'lunch', 'dinner', 'snack'),
    description     varchar(255) NOT NULL,
    calories		int NOT NULL,
    protein         int NOT NULL,
    carbs           int NOT NULL,
    fats            int NOT NULL,
    notes		    varchar(255) NOT NULL,
    FOREIGN KEY (userId) REFERENCES Account(id)
);

CREATE TABLE MentalHealthReport (
	userId				int NOT NULL,
    logDate				date NOT NULL,
    mood                ENUM ('happy', 'calm', 'anxious', 'stressed', 'sad', 'angry', 'other'),
    -- some score determined by the response in the applet (ex add up question answers ranked on scale 1-10) --
    stressScore		    int NOT NULL,
    sleepDuration		int NOT NULL,
    journal             varchar(255),
    FOREIGN KEY (userID) REFERENCES Account(id)
);

CREATE TABLE UserActivity (
    userId              int NOT NULL,
    activityDescription varchar(25) NOT NULL,
    activityTime        datetime NOT NULL,
    FOREIGN KEY (userId) REFERENCES Account(id)
);