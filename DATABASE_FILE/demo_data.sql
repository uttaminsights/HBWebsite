-- ---------------------------------------------------------------------------
-- OPTIONAL DEMO DATA for Nivaara (portfolio / local testing only)
-- Fictional guests (example.com emails) with completed stays and reviews so the
-- review sections, star ratings and admin lists have something to show.
-- Import AFTER hbwebsite.sql. Do NOT import on a live site: invented reviews
-- shown to real customers count as fake reviews.
-- Demo guests cannot log in (random password).
-- To remove later: DELETE the users whose email ends in @example.com along
-- with their rating_review, booking_details and booking_order rows.
-- ---------------------------------------------------------------------------

START TRANSACTION;

-- Aarav Mehta
INSERT INTO `user_cred` (`name`,`email`,`address`,`phonenum`,`pincode`,`dob`,`profile`,`password`,`is_verified`,`status`) VALUES ('Aarav Mehta','aarav.mehta@example.com','Mumbai, Maharashtra','9000000001',211001,'1994-01-01','','$2y$10$qNHopEp41h3XJSPD.vNKV.Kj913s10YhQs.SPgnYORM0OjViH4hle',1,1);
SET @u = LAST_INSERT_ID();
INSERT INTO `booking_order` (`user_id`,`room_id`,`check_in`,`check_out`,`arrival`,`refund`,`booking_status`,`order_id`,`trans_id`,`trans_amt`,`trans_status`,`trans_resp_msg`,`rate_review`,`datentime`) VALUES (@u,6,'2026-08-14','2026-08-16',1,NULL,'booked','DEMO_ORD_001','DEMO_TXN_1',1800,'TXN_SUCCESS','Demo data',1,'2026-08-02 10:30:00');
SET @b = LAST_INSERT_ID();
INSERT INTO `booking_details` (`booking_id`,`room_name`,`price`,`total_pay`,`room_no`,`user_name`,`phonenum`,`address`) VALUES (@b,'Supreme Deluxe Room',900,1800,'101','Aarav Mehta','9000000001','Mumbai, Maharashtra');
INSERT INTO `rating_review` (`booking_id`,`room_id`,`user_id`,`rating`,`review`,`seen`,`datentime`) VALUES (@b,6,@u,5,'The suite was huge and spotless. Watching the sunset from the terrace was the highlight of our anniversary trip, and the staff even left us a handwritten note!',1,'2026-08-16 18:00:00');

-- Kabir Khanna
INSERT INTO `user_cred` (`name`,`email`,`address`,`phonenum`,`pincode`,`dob`,`profile`,`password`,`is_verified`,`status`) VALUES ('Kabir Khanna','kabir.khanna@example.com','New Delhi, Delhi','9000000002',211001,'1994-01-01','','$2y$10$qNHopEp41h3XJSPD.vNKV.Kj913s10YhQs.SPgnYORM0OjViH4hle',1,1);
SET @u = LAST_INSERT_ID();
INSERT INTO `booking_order` (`user_id`,`room_id`,`check_in`,`check_out`,`arrival`,`refund`,`booking_status`,`order_id`,`trans_id`,`trans_amt`,`trans_status`,`trans_resp_msg`,`rate_review`,`datentime`) VALUES (@u,6,'2026-09-02','2026-09-05',1,NULL,'booked','DEMO_ORD_002','DEMO_TXN_2',2700,'TXN_SUCCESS','Demo data',1,'2026-08-21 10:30:00');
SET @b = LAST_INSERT_ID();
INSERT INTO `booking_details` (`booking_id`,`room_name`,`price`,`total_pay`,`room_no`,`user_name`,`phonenum`,`address`) VALUES (@b,'Supreme Deluxe Room',900,2700,'102','Kabir Khanna','9000000002','New Delhi, Delhi');
INSERT INTO `rating_review` (`booking_id`,`room_id`,`user_id`,`rating`,`review`,`seen`,`datentime`) VALUES (@b,6,@u,5,'Worth every rupee. Super comfy bed, fast Wi-Fi for my work calls and an excellent breakfast spread. Check-in took two minutes.',1,'2026-09-05 18:00:00');

-- Priya Nair
INSERT INTO `user_cred` (`name`,`email`,`address`,`phonenum`,`pincode`,`dob`,`profile`,`password`,`is_verified`,`status`) VALUES ('Priya Nair','priya.nair@example.com','Kochi, Kerala','9000000003',211001,'1994-01-01','','$2y$10$qNHopEp41h3XJSPD.vNKV.Kj913s10YhQs.SPgnYORM0OjViH4hle',1,1);
SET @u = LAST_INSERT_ID();
INSERT INTO `booking_order` (`user_id`,`room_id`,`check_in`,`check_out`,`arrival`,`refund`,`booking_status`,`order_id`,`trans_id`,`trans_amt`,`trans_status`,`trans_resp_msg`,`rate_review`,`datentime`) VALUES (@u,5,'2026-08-21','2026-08-23',1,NULL,'booked','DEMO_ORD_003','DEMO_TXN_3',1200,'TXN_SUCCESS','Demo data',1,'2026-08-09 10:30:00');
SET @b = LAST_INSERT_ID();
INSERT INTO `booking_details` (`booking_id`,`room_name`,`price`,`total_pay`,`room_no`,`user_name`,`phonenum`,`address`) VALUES (@b,'Luxury Room',600,1200,'103','Priya Nair','9000000003','Kochi, Kerala');
INSERT INTO `rating_review` (`booking_id`,`room_id`,`user_id`,`rating`,`review`,`seen`,`datentime`) VALUES (@b,5,@u,5,'Big windows, a calm vibe and a really comfortable sofa. They let us check in early with zero fuss. We will be back next season.',1,'2026-08-23 18:00:00');

-- Rohan Kapoor
INSERT INTO `user_cred` (`name`,`email`,`address`,`phonenum`,`pincode`,`dob`,`profile`,`password`,`is_verified`,`status`) VALUES ('Rohan Kapoor','rohan.kapoor@example.com','Lucknow, Uttar Pradesh','9000000004',211001,'1994-01-01','','$2y$10$qNHopEp41h3XJSPD.vNKV.Kj913s10YhQs.SPgnYORM0OjViH4hle',1,1);
SET @u = LAST_INSERT_ID();
INSERT INTO `booking_order` (`user_id`,`room_id`,`check_in`,`check_out`,`arrival`,`refund`,`booking_status`,`order_id`,`trans_id`,`trans_amt`,`trans_status`,`trans_resp_msg`,`rate_review`,`datentime`) VALUES (@u,5,'2026-09-10','2026-09-11',1,NULL,'booked','DEMO_ORD_004','DEMO_TXN_4',600,'TXN_SUCCESS','Demo data',1,'2026-08-29 10:30:00');
SET @b = LAST_INSERT_ID();
INSERT INTO `booking_details` (`booking_id`,`room_name`,`price`,`total_pay`,`room_no`,`user_name`,`phonenum`,`address`) VALUES (@b,'Luxury Room',600,600,'104','Rohan Kapoor','9000000004','Lucknow, Uttar Pradesh');
INSERT INTO `rating_review` (`booking_id`,`room_id`,`user_id`,`rating`,`review`,`seen`,`datentime`) VALUES (@b,5,@u,4,'Lovely room and a very helpful front desk. Only wish the gym opened a little earlier - otherwise a perfect stay.',1,'2026-09-11 18:00:00');

-- Sneha Iyer
INSERT INTO `user_cred` (`name`,`email`,`address`,`phonenum`,`pincode`,`dob`,`profile`,`password`,`is_verified`,`status`) VALUES ('Sneha Iyer','sneha.iyer@example.com','Bengaluru, Karnataka','9000000005',211001,'1994-01-01','','$2y$10$qNHopEp41h3XJSPD.vNKV.Kj913s10YhQs.SPgnYORM0OjViH4hle',1,1);
SET @u = LAST_INSERT_ID();
INSERT INTO `booking_order` (`user_id`,`room_id`,`check_in`,`check_out`,`arrival`,`refund`,`booking_status`,`order_id`,`trans_id`,`trans_amt`,`trans_status`,`trans_resp_msg`,`rate_review`,`datentime`) VALUES (@u,4,'2026-07-30','2026-08-02',1,NULL,'booked','DEMO_ORD_005','DEMO_TXN_5',1500,'TXN_SUCCESS','Demo data',1,'2026-07-18 10:30:00');
SET @b = LAST_INSERT_ID();
INSERT INTO `booking_details` (`booking_id`,`room_name`,`price`,`total_pay`,`room_no`,`user_name`,`phonenum`,`address`) VALUES (@b,'Deluxe Room',500,1500,'105','Sneha Iyer','9000000005','Bengaluru, Karnataka');
INSERT INTO `rating_review` (`booking_id`,`room_id`,`user_id`,`rating`,`review`,`seen`,`datentime`) VALUES (@b,4,@u,5,'Warm, cosy and beautifully lit. The balcony was perfect for morning chai. Great value for the price.',1,'2026-08-02 18:00:00');

-- Vikram Singh
INSERT INTO `user_cred` (`name`,`email`,`address`,`phonenum`,`pincode`,`dob`,`profile`,`password`,`is_verified`,`status`) VALUES ('Vikram Singh','vikram.singh@example.com','Jaipur, Rajasthan','9000000006',211001,'1994-01-01','','$2y$10$qNHopEp41h3XJSPD.vNKV.Kj913s10YhQs.SPgnYORM0OjViH4hle',1,1);
SET @u = LAST_INSERT_ID();
INSERT INTO `booking_order` (`user_id`,`room_id`,`check_in`,`check_out`,`arrival`,`refund`,`booking_status`,`order_id`,`trans_id`,`trans_amt`,`trans_status`,`trans_resp_msg`,`rate_review`,`datentime`) VALUES (@u,4,'2026-09-18','2026-09-20',1,NULL,'booked','DEMO_ORD_006','DEMO_TXN_6',1000,'TXN_SUCCESS','Demo data',1,'2026-09-06 10:30:00');
SET @b = LAST_INSERT_ID();
INSERT INTO `booking_details` (`booking_id`,`room_name`,`price`,`total_pay`,`room_no`,`user_name`,`phonenum`,`address`) VALUES (@b,'Deluxe Room',500,1000,'106','Vikram Singh','9000000006','Jaipur, Rajasthan');
INSERT INTO `rating_review` (`booking_id`,`room_id`,`user_id`,`rating`,`review`,`seen`,`datentime`) VALUES (@b,4,@u,4,'Clean room, quiet floor and quick room service. Parking was easy too. A good pick for a short family trip.',1,'2026-09-20 18:00:00');

-- Ananya Sharma
INSERT INTO `user_cred` (`name`,`email`,`address`,`phonenum`,`pincode`,`dob`,`profile`,`password`,`is_verified`,`status`) VALUES ('Ananya Sharma','ananya.sharma@example.com','Pune, Maharashtra','9000000007',211001,'1994-01-01','','$2y$10$qNHopEp41h3XJSPD.vNKV.Kj913s10YhQs.SPgnYORM0OjViH4hle',1,1);
SET @u = LAST_INSERT_ID();
INSERT INTO `booking_order` (`user_id`,`room_id`,`check_in`,`check_out`,`arrival`,`refund`,`booking_status`,`order_id`,`trans_id`,`trans_amt`,`trans_status`,`trans_resp_msg`,`rate_review`,`datentime`) VALUES (@u,3,'2026-08-05','2026-08-07',1,NULL,'booked','DEMO_ORD_007','DEMO_TXN_7',600,'TXN_SUCCESS','Demo data',1,'2026-07-24 10:30:00');
SET @b = LAST_INSERT_ID();
INSERT INTO `booking_details` (`booking_id`,`room_name`,`price`,`total_pay`,`room_no`,`user_name`,`phonenum`,`address`) VALUES (@b,'Simple Room',300,600,'107','Ananya Sharma','9000000007','Pune, Maharashtra');
INSERT INTO `rating_review` (`booking_id`,`room_id`,`user_id`,`rating`,`review`,`seen`,`datentime`) VALUES (@b,3,@u,4,'Small but smartly designed - everything I needed for a work trip. Hot water, a proper desk and strong Wi-Fi.',1,'2026-08-07 18:00:00');

-- Meera Joshi
INSERT INTO `user_cred` (`name`,`email`,`address`,`phonenum`,`pincode`,`dob`,`profile`,`password`,`is_verified`,`status`) VALUES ('Meera Joshi','meera.joshi@example.com','Varanasi, Uttar Pradesh','9000000008',211001,'1994-01-01','','$2y$10$qNHopEp41h3XJSPD.vNKV.Kj913s10YhQs.SPgnYORM0OjViH4hle',1,1);
SET @u = LAST_INSERT_ID();
INSERT INTO `booking_order` (`user_id`,`room_id`,`check_in`,`check_out`,`arrival`,`refund`,`booking_status`,`order_id`,`trans_id`,`trans_amt`,`trans_status`,`trans_resp_msg`,`rate_review`,`datentime`) VALUES (@u,3,'2026-09-25','2026-09-26',1,NULL,'booked','DEMO_ORD_008','DEMO_TXN_8',300,'TXN_SUCCESS','Demo data',1,'2026-09-13 10:30:00');
SET @b = LAST_INSERT_ID();
INSERT INTO `booking_details` (`booking_id`,`room_name`,`price`,`total_pay`,`room_no`,`user_name`,`phonenum`,`address`) VALUES (@b,'Simple Room',300,300,'108','Meera Joshi','9000000008','Varanasi, Uttar Pradesh');
INSERT INTO `rating_review` (`booking_id`,`room_id`,`user_id`,`rating`,`review`,`seen`,`datentime`) VALUES (@b,3,@u,5,'Best budget stay I have had in Prayagraj. Friendly staff, a comfy bed and only a short walk to Civil Lines.',1,'2026-09-26 18:00:00');

COMMIT;
