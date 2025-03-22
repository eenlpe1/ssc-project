import nodemailer from 'nodemailer';

/**
 * Create a transporter for sending emails
 * @returns {nodemailer.Transporter} Nodemailer transporter
 */
export function createTransporter() {
    // Get email configuration from environment variables
    const email = process.env.EMAIL;
    const password = process.env.EMAIL_KEY;
    
    if (!email || !password) {
        console.error('Email configuration is missing. Please set EMAIL and EMAIL_KEY in your .env file.');
        return null;
    }
    
    // Create a transporter using Gmail SMTP
    const transporter = nodemailer.createTransport({
        service: 'gmail',
        auth: {
            user: email,
            pass: password
        }
    });
    
    return transporter;
}

/**
 * Send an email with verification code
 * @param {string} to - Recipient email
 * @param {string} code - Verification code
 * @param {string} subject - Email subject
 * @returns {Promise} - Promise resolving to email send result
 */
export async function sendVerificationEmail(to, code, subject = 'Password Reset Verification Code') {
    const transporter = createTransporter();
    
    if (!transporter) {
        return Promise.reject(new Error('Email transporter could not be created'));
    }
    
    const mailOptions = {
        from: process.env.EMAIL,
        to,
        subject,
        text: `Your verification code is: ${code}. This code will expire in 15 minutes.`,
        html: `
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 5px;">
                <h2 style="color: #1e3a8a; text-align: center;">Password Reset Verification</h2>
                <p>You requested a password reset. Please use the following verification code:</p>
                <div style="text-align: center; margin: 30px 0;">
                    <span style="font-size: 24px; font-weight: bold; background-color: #f5f5f5; padding: 10px 20px; border-radius: 5px; letter-spacing: 5px;">${code}</span>
                </div>
                <p>This code will expire in 15 minutes.</p>
                <p>If you did not request this password reset, please ignore this email.</p>
                <div style="text-align: center; margin-top: 30px; color: #666; font-size: 12px;">
                    <p>SSC Project Management Tool</p>
                </div>
            </div>
        `
    };
    
    return transporter.sendMail(mailOptions);
}

export default {
    createTransporter,
    sendVerificationEmail
}; 