// Email server script to handle sending emails with nodemailer
import express from 'express';
import dotenv from 'dotenv';
import cors from 'cors';
import { sendVerificationEmail } from './utils/mailer.js';
import path from 'path';
import { fileURLToPath } from 'url';

// Set up proper paths for ES modules
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const rootPath = path.resolve(__dirname, '../../');

// Load environment variables from project root
dotenv.config({ path: path.join(rootPath, '.env') });

// Check if email config is loaded
console.log('EMAIL config status:', process.env.EMAIL ? 'Found' : 'Not found');

const app = express();
const PORT = process.env.EMAIL_SERVER_PORT || 3333;

// Middleware
app.use(cors());
app.use(express.json());

// Routes
app.post('/send-verification', async (req, res) => {
    try {
        const { email, code } = req.body;
        
        if (!email || !code) {
            return res.status(400).json({ 
                success: false, 
                message: 'Email and verification code are required' 
            });
        }
        
        await sendVerificationEmail(email, code);
        
        return res.status(200).json({ 
            success: true, 
            message: 'Verification code sent successfully' 
        });
    } catch (error) {
        console.error('Error sending verification email:', error);
        return res.status(500).json({ 
            success: false, 
            message: 'Failed to send verification email',
            error: error.message
        });
    }
});

// Health check endpoint
app.get('/health', (req, res) => {
    res.status(200).json({ status: 'Email server is running' });
});

// Start server
app.listen(PORT, () => {
    console.log(`Email server running on port ${PORT}`);
}); 