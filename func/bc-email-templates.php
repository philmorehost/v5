<?php
	//User Registration
	createVendorEmailTemplateIfNotExists(
		"user-reg",
		"Welcome to Our Platform - Complete Your Registration",
		"Dear {firstname} {lastname},\n\nThank you for registering with us. We are excited to have you on board!\n\nPlease find below the details you provided during registration:\n\n- Email address: {email}\n- Phone number: {phone}\n- Username: {username}\n- Home address: {address}\n\nIf you have any questions or need assistance, feel free to contact us.\n\nBest regards,\nThe Support Team"
	);

	//User Login
	createVendorEmailTemplateIfNotExists(
		"user-log",
		"User Login Notification",
		"Hello {firstname} {lastname},\n\nYour login details are as follows:\nUsername: {username}\nIP Address: {ip_address}\n\nThank you,\nThe Support Team"
	);

	//User Password Update
	createVendorEmailTemplateIfNotExists(
		"user-pass-update",
		"Password Update Notification",
		"Dear {firstname} {lastname},\n\nYour password has been successfully updated.\n\nIf you did not make this change, please contact our support team immediately.\n\nBest regards,\nThe Support Team"
	);

	//User Account Update
	createVendorEmailTemplateIfNotExists(
		"user-account-update",
		"Account Information Updated",
		"Dear {firstname} {lastname},\n\nYour account information has been successfully updated.\n\nDetails:\nEmail: {email}\nPhone: {phone}\nAddress: {address}\nSecurity Answer: {security_answer}\n\nThank you for keeping your information up to date.\n\nSincerely,\nThe Support Team"
	);

	//User Account Recovery
	createVendorEmailTemplateIfNotExists(
		"user-account-recovery",
		"Password Recovery",
		"Hello {firstname} {lastname},\n\nWe received a request to recover your account password.\n\nYour recovery code is: {recovery_code}\n\nPlease use this code to reset your password.\n\nBest regards,\nThe Support Team"
	);

	//User Account Status
	createVendorEmailTemplateIfNotExists(
		"user-account-status",
		"User Account Status Update",
		"Hello {firstname} {lastname},\n\nWe are writing to inform you about your account status.\n\nYour account is currently {account_status}.\n\nThank you.\n\nSincerely,\nThe Management"
	);

	//User API Status
	createVendorEmailTemplateIfNotExists(
		"user-api-status",
		"User API Status Update",
		"Hello {firstname}, {lastname}\n\nWe wanted to inform you about the current status of your API:\n\n{api_status}\n\nBest regards,\nThe API Team"
	);

	//User 
	createVendorEmailTemplateIfNotExists(
		"user-upgrade",
		"Upgrade Notification",
		"Hello {firstname} {lastname},\n\nWe are pleased to inform you that your account has been upgraded to {account_level}. Thank you for choosing our service.\n\nBest regards,\nThe Team"
	);

	//User Referral Commission
	createVendorEmailTemplateIfNotExists(
		"user-referral-commission",
		"Referral Commission Earned",
		"Hello {firstname} {lastname},\n\nWe are pleased to inform you that you have earned a referral commission of {referral_commission}.\n\nThe commission was earned from your referral, {referree}, who is currently at {account_level} account level.\n\nThank you for your participation in our referral program!\n\nBest regards,\nThe Support Team"
	);

	//User Transaction (Admin)
	createVendorEmailTemplateIfNotExists(
		"user-transactions",
		"Transaction Details",
		"Hello {admin_firstname}, {admin_lastname}\n\nA transaction has been made by user {username}, {firstname}.\n\nPrevious balance: {balance_before}\nNew balance: {balance_after}\n\nAmount: {amount}\nDescription: {description}\nType: {type}"
	);

	//User Credit/Debit Transaction
	createVendorEmailTemplateIfNotExists(
		"user-funding",
		"Account Update: Transaction Details",
    	"Hello {firstname} {lastname},\n\nYour account has been updated with the following transaction details:\n\n- Balance Before: {balance_before}\n- Balance After: {balance_after}\n- Amount: {amount}\n- Description: {description}\n- Type: {type}\n\nIf you have any questions or concerns, feel free to reach out to us.\n\nBest regards,\nThe Support Team"
	);

	//User Refund
	createVendorEmailTemplateIfNotExists(
		"user-refund",
		"Refund Notification",
		"Dear {firstname} {lastname},\n\nWe are pleased to inform you that a refund has been processed for you.\n\nAmount: {amount}\nDescription: {description}\n\nIf you have any questions or concerns, feel free to reach out to our customer support team.\n\nBest regards,\nThe Support Team"
	);

	//Vendor Registration
	createSuperAdminEmailTemplateIfNotExists(
		"vendor-reg",
		"Vendor Registration Confirmation",
		"Hello,\n\nWe are delighted to welcome you as a potential vendor.\n\nPlease find below the details you provided:\n\nFirst Name: {firstname}\nLast Name: {lastname}\nEmail: {email}\nPhone: {phone}\nAddress: {address}\nWebsite: {website}\n\nThank you for your interest in partnering with us.\n\nBest regards,\nThe Vendor Registration Team"
	);

	//Vendor Login
	createSuperAdminEmailTemplateIfNotExists(
		"vendor-log",
		"Vendor Login Notification",
		"Hello {firstname}, {lastname},\n\nWe wanted to inform you that there has been a login to your vendor account. Here are the details:\n\nName: {firstname}, {lastname}\nEmail: {email}\nIP Address: {ip_address}\n\nIf this login was not authorized by you, please contact support immediately.\n\nBest regards,\nThe Vendor Management Team"
	);

	//Vendor Password Update
	createSuperAdminEmailTemplateIfNotExists(
		"vendor-pass-update",
		"Password Update Required",
		"Hello {firstname} {lastname},\n\nWe kindly remind you to update your password as soon as possible. This is essential for the security of your account.\n\nThank you for your attention to this matter.\n\nBest regards,\nThe Vendor Team"
	);

	//Vendor Account Update
	createSuperAdminEmailTemplateIfNotExists(
		"vendor-account-update",
		"Vendor Account Update",
		"Dear {firstname} {lastname},\n\nYour account information has been updated successfully.\n\nHere are your updated details:\nEmail: {email}\nPhone: {phone}\nAddress: {address}\nWebsite: {website}\n\nIf you have any questions or concerns, please feel free to reach out to us.\n\nBest regards,\nThe Vendor Management Team"
	);

	//Vendor Password Recovery
	createSuperAdminEmailTemplateIfNotExists(
		"vendor-account-recovery",
		"Password Recovery",
		"Hello {firstname} {lastname},\n\nYou have requested a password recovery for your vendor account.\n\nPlease use the following recovery code to reset your password: {recovery_code}\n\nIf you did not request this, please disregard this email.\n\nBest regards,\nThe Vendor Team"
	);

	//Vendor Account Status
	createSuperAdminEmailTemplateIfNotExists(
		"vendor-account-status",
		"Vendor Account Status",
		"Hello {firstname}, {lastname}\n\nYour account status is: {account_status}"
	);

	//Vendor Transaction (Super Admin)
	createSuperAdminEmailTemplateIfNotExists(
		"vendor-transactions",
		"Vendor Transaction Details",
		"Hello {admin_firstname} {admin_lastname},\n\nA new transaction has been made by a vendor. Here are the details:\n\nVendor Email: {email}\nVendor Name: {firstname}\nBalance Before Transaction: {balance_before}\nBalance After Transaction: {balance_after}\nTransaction Amount: {amount}\nDescription: {description}\nTransaction Type: {type}\n\nBest regards,\nThe Admin Team"
	);

	//Vendor Credit/Debit
	createSuperAdminEmailTemplateIfNotExists(
		"vendor-funding",
		"Vendor Credit/Debit Notification",
		"Hello {firstname} {lastname},\n\nThis email is to inform you about a recent transaction on your account.\n\nBalance Before: {balance_before}\nBalance After: {balance_after}\nAmount: {amount}\nDescription: {description}\nType: {type}\n\nThank you.\n"
	);

	//Vendor Refund Notification
	createSuperAdminEmailTemplateIfNotExists(
		"vendor-refund",
		"Vendor Refund Notification",
		"Hello {firstname} {lastname},\n\nWe are writing to inform you that a refund has been issued to you in the amount of {amount} for the following reason:\n\n{description}\n\nThank you.\n\nSincerely,\nThe Management Team"
	);

?>