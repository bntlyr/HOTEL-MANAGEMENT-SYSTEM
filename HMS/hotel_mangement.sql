-- Create database
CREATE DATABASE IF NOT EXISTS hotel_management;
USE hotel_management;


-- Staffs Table
CREATE TABLE staffs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fname VARCHAR(50) NOT NULL,
    lname VARCHAR(50) NOT NULL,
    bod DATE NOT NULL,
    gender VARCHAR(10) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    street_address VARCHAR(100) NOT NULL,
    street_address2 VARCHAR(100),
    country VARCHAR(50) NOT NULL,
    city VARCHAR(50) NOT NULL,
    region VARCHAR(50) NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    id_card_type int(11) NOT NULL,
    id_card_no varchar(20) NOT NULL,
    hiring_date DATE NOT NULL,
    shiftid INT NOT NULL,
    staff_type_id INT NOT NULL,
    salary DECIMAL(10, 2) NOT NULL
);
INSERT INTO staffs (fname, lname, bod, gender, email, phone, street_address, street_address2, country, city, region, postal_code, id_card_type, id_card_no, hiring_date, shiftid, staff_type_id, salary) VALUES
('John', 'Doe', '1985-03-15', 'Male', 'johndoe@example.com', '1234567890', '123 Main St', 'Apt 4', 'USA', 'New York', 'NY', '10001', 1, 'ID123456', '2020-01-15', 1, 1, 50000.00),
('Jane', 'Smith', '1990-07-21', 'Female', 'janesmith@example.com', '2345678901', '456 Elm St', '', 'USA', 'Los Angeles', 'CA', '90001', 2, 'DL654321', '2019-04-22', 2, 2, 60000.00),
('Michael', 'Brown', '1983-11-02', 'Male', 'michaelbrown@example.com', '3456789012', '789 Oak St', '', 'USA', 'Chicago', 'IL', '60601', 1, 'ID789012', '2018-11-11', 3, 3, 55000.00),
('Emily', 'Davis', '1992-12-12', 'Female', 'emilydavis@example.com', '4567890123', '321 Pine St', 'Suite 5', 'USA', 'Houston', 'TX', '77001', 2, 'DL123789', '2021-03-05', 1, 1, 62000.00),
('Daniel', 'Wilson', '1987-04-18', 'Male', 'danielwilson@example.com', '5678901234', '654 Maple St', '', 'USA', 'Phoenix', 'AZ', '85001', 1, 'ID456789', '2017-07-10', 2, 2, 48000.00),
('Olivia', 'Martinez', '1995-09-09', 'Female', 'oliviamartinez@example.com', '6789012345', '987 Cedar St', '', 'USA', 'Philadelphia', 'PA', '19101', 2, 'DL789456', '2020-09-20', 3, 3, 59000.00),
('Christopher', 'Anderson', '1984-06-06', 'Male', 'christopheranderson@example.com', '7890123456', '159 Birch St', '', 'USA', 'San Antonio', 'TX', '78201', 1, 'ID147258', '2019-05-25', 1, 1, 53000.00),
('Sophia', 'Thomas', '1993-08-28', 'Female', 'sophiathomas@example.com', '8901234567', '753 Spruce St', 'Apt 2', 'USA', 'San Diego', 'CA', '92101', 2, 'DL852369', '2018-12-30', 2, 2, 61000.00),
('Matthew', 'Taylor', '1986-10-19', 'Male', 'matthewtaylor@example.com', '9012345678', '258 Willow St', '', 'USA', 'Dallas', 'TX', '75201', 1, 'ID369852', '2017-02-15', 3, 3, 47000.00),
('Ava', 'Harris', '1991-05-14', 'Female', 'avaharris@example.com', '0123456789', '357 Aspen St', 'Suite 3', 'USA', 'San Jose', 'CA', '95101', 2, 'DL963741', '2021-06-15', 1, 1, 60000.00);

CREATE TABLE `shift` (
  `shift_id` int(10) NOT NULL,
  `shift` varchar(100) NOT NULL,
  `shift_timing` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



INSERT INTO `shift` (`shift_id`, `shift`, `shift_timing`) VALUES
(1, 'Morning', '5:00 AM - 10:00 AM'),
(2, 'Day', '10:00 AM - 4:00PM'),
(3, 'Evening', '4:00 PM - 10:00 PM'),
(4, 'Night', '10:00PM - 5:00AM');

CREATE TABLE `staff_type` (
  `staff_type_id` int(10) NOT NULL,
  `staff_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



INSERT INTO `staff_type` (`staff_type_id`, `staff_type`) VALUES
(1, 'Manager'),
(2, 'Housekeeping Manager'),
(3, 'Front Desk Receptionist'),
(4, 'Chef'),
(5, 'Waiter'),
(6, 'Room Attendant'),
(7, 'Concierge'),
(8, 'Hotel Maintenance Engineer'),
(9, 'Hotel Sales Manager');

CREATE TABLE `id_card_type` (
  `id_card_type_id` int(10) NOT NULL,
  `id_card_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `id_card_type`
--

INSERT INTO `id_card_type` (`id_card_type_id`, `id_card_type`) VALUES
(1, 'National Identity Card'),
(2, 'Voter Id Card'),
(3, 'Pan Card'),
(4, 'Driving License');

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

INSERT INTO admins (username, password) VALUES ('admin', 'goodwill69');


-- Create table for Guests
CREATE TABLE Guests (
    guestID INT PRIMARY KEY AUTO_INCREMENT,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    address VARCHAR(100),
    city VARCHAR(50),
    state VARCHAR(50),
    zipCode VARCHAR(20),
    country VARCHAR(50),
    phoneNumber VARCHAR(20),
    emailAddress VARCHAR(100),
    gender VARCHAR(10)
);

-- Create table for Hotels
CREATE TABLE Hotels (
    hotelID INT PRIMARY KEY AUTO_INCREMENT,
    hotelName VARCHAR(100) NOT NULL,
    address VARCHAR(100),
    city VARCHAR(50),   
    state VARCHAR(50),
    zipCode VARCHAR(20),
    country VARCHAR(50)
);
INSERT INTO Hotels (hotelName, address, city, state, zipCode, country)
VALUES ('Goodwill Hotel', 'Eulogio Rodriguez Jr. Ave', 'Pasig', 'Metro Manila', '1600', 'Philippines');

-- Room Type Table
CREATE TABLE IF NOT EXISTS room_type (
    room_type_id INT AUTO_INCREMENT PRIMARY KEY,
    room_type VARCHAR(50) NOT NULL
);

INSERT INTO room_type (room_type) VALUES
('Standard'),
('Deluxe'),
('Suite');

-- Room Status Table
CREATE TABLE IF NOT EXISTS room_status (
    room_status_id INT AUTO_INCREMENT PRIMARY KEY,
    room_status VARCHAR(50) NOT NULL
);

INSERT INTO room_status (room_status) VALUES
('Available'),
('Occupied'),
('Under Maintenance');

CREATE TABLE IF NOT EXISTS Rooms (
    roomID INT PRIMARY KEY AUTO_INCREMENT,
    hotelID INT,
    roomNumber VARCHAR(20),
    roomType VARCHAR(50),
    roomTypeID INT,
    roomStatus VARCHAR(50),
    roomStatusID INT,
    floor INT,
    description TEXT,
    rate DECIMAL(10, 2),
    FOREIGN KEY (hotelID) REFERENCES Hotels(hotelID),
    FOREIGN KEY (roomTypeID) REFERENCES room_type(room_type_id),
    FOREIGN KEY (roomStatusID) REFERENCES room_status(room_status_id)
);


-- Create table for Bookings
CREATE TABLE Bookings (
    bookingID INT PRIMARY KEY AUTO_INCREMENT,
    hotelID INT,
    guestID INT,
    bookingStatusID INT,
    dateFrom DATE,
    dateTo DATE,
    roomCount INT,
    FOREIGN KEY (hotelID) REFERENCES Hotels(hotelID),
    FOREIGN KEY (guestID) REFERENCES Guests(guestID)
);

-- Create table for Payments
CREATE TABLE Payments (
    paymentID INT PRIMARY KEY AUTO_INCREMENT,
    bookingID INT,
    paymentMethod VARCHAR(50),
    paymentStatusID INT,
    date DATETIME,
    amount DECIMAL(10, 2),
    cardType VARCHAR(50),
    cardNumber VARCHAR(50),
    expiryDate VARCHAR(20),
    cvv VARCHAR(10),
    gcashAccount VARCHAR(100),
    gcashNumber VARCHAR(20),
    FOREIGN KEY (bookingID) REFERENCES Bookings(bookingID)
);

-- Create table for RoomsBooked
CREATE TABLE RoomsBooked (
    roomsBookedID INT PRIMARY KEY AUTO_INCREMENT,
    bookingID INT,
    roomID INT,
    rate DECIMAL(10, 2),
    dateFrom DATE,
    dateTo DATE,
    FOREIGN KEY (bookingID) REFERENCES Bookings(bookingID),
    FOREIGN KEY (roomID) REFERENCES Rooms(roomID)
);