CREATE TABLE Account (
	id			int PRIMARY KEY AUTO_INCREMENT,
	email		varchar(50) UNIQUE NOT NULL,
    password	varchar(50) NOT NULL,
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
    dueDate			date,
    FOREIGN KEY (userId) REFERENCES Account(id)
);

-- user goals to configure --
CREATE TABLE UserGoals (
    userId		            int PRIMARY KEY,
	dailyExercise			time,
    dailyCaloriesConsumed	float,
    dailyCaloriesBurned		float,
    desiredWeight			float
);

CREATE TABLE WeightReport (
	userId	int NOT NULL,
	weight	float NOT NULL,
    logDate	date NOT NULL,
    FOREIGN KEY (userId) REFERENCES Account(id)
);

CREATE TABLE ExerciseReport (
	userId 			int NOT NULL,
    category		ENUM ('Aerobic', 'Strength', 'Stretching', 'Other')  NOT NULL,
    duration		time NOT NULL,
    burnedCalories  float NOT NULL,
    logDate			date NOT NULL,
	FOREIGN KEY (userId) REFERENCES Account(id)
);

CREATE TABLE MealReport (
	userId			int NOT NULL,
    category		ENUM ('Fruit', 'Vegetable', 'Grain', 'Protein', 'Dairy', 'Fat', 'Snack', 'Sweet', 'Beverage') NOT NULL,
    description		varchar(25) NOT NULL,
    calories		float NOT NULL,
    logDate			date NOT NULL,
    FOREIGN KEY (userId) REFERENCES Account(id)
);

CREATE TABLE MentalHealthReport (
	userId				int NOT NULL,
    -- some score determined by the response in the applet (ex add up question answers ranked on scale 1-10) --
    anxietyScore		int NOT NULL,
    depressionScore		int NOT NULL,
    logDate				date NOT NULL,
    FOREIGN KEY (userID) REFERENCES Account(id)
);

CREATE TABLE UserActivity (
    userId              int NOT NULL,
    activityDescription varchar(25) NOT NULL,
    activityTime        date NOT NULL,
    FOREIGN KEY (userId) REFERENCES Account(id)
);