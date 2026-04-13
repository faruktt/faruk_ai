# ⚡ Faruk AI - Smart Search Assistant

Faruk AI হলো একটি আধুনিক AI-powered search ইঞ্জিন। এটি ইন্টারনেটের রিয়েল-টাইম ডাটা ব্যবহার করে আপনার প্রশ্নের সঠিক এবং তথ্যবহুল উত্তর প্রদান করে। এটি **Groq AI (Llama-3.3-70b)** এবং **Tavily Search API** এর সমন্বয়ে তৈরি করা হয়েছে।

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![Laravel](https://img.shields.io/badge/Framework-Laravel_11-red.svg)
![Tailwind](https://img.shields.io/badge/CSS-Tailwind-06B6D4.svg)
![License](https://img.shields.io/badge/License-MIT-green.svg)

## ✨ মূল ফিচারসমূহ (Features)

- 🔍 **Real-time Web Search:** Tavily API ব্যবহার করে ইন্টারনেটের লেটেস্ট তথ্য খুঁজে বের করে।
- 🧠 **Advanced AI Reasoning:** Groq Cloud-এর মাধ্যমে Llama-3.3-70b মডেল ব্যবহার করে দ্রুত উত্তর প্রদান।
- 💬 **Conversation History:** ব্যবহারকারীরা তাদের আগের সব চ্যাট হিস্ট্রি দেখতে পারে (Database persistence)।
- 📝 **Markdown & Code Support:** চ্যাটবক্সের ভেতর কোড হাইলাইটিং এবং সুন্দর মার্কডাউন ফরম্যাটিং।
- 📱 **Fully Responsive:** মোবাইল এবং ডেক্সটপ সব ডিভাইসে ব্যবহারযোগ্য।
- 🔒 **User Authentication:** লগইন সিস্টেম এবং ইউজার-ভিত্তিক ডাটা ম্যানেজমেন্ট।
- 🚦 **Usage Limits:** প্রতিটি কনভারসেশনে ৪টি করে ফলো-আপ প্রশ্নের লিমিট সিস্টেম।

## 🛠 টেক স্ট্যাক (Tech Stack)

| ক্যাটাগরি | টেকনোলজি |
| :--- | :--- |
| **Backend** | Laravel 11 (PHP) |
| **Frontend** | Tailwind CSS, Alpine.js (Optional), FontAwesome |
| **Database** | MySQL |
| **AI Model** | Groq (Llama-3.3-70b-versatile) |
| **Search Engine** | Tavily Search API |
| **Rendering** | Marked.js (Markdown), Highlight.js (Code highlighting) |

## 🚀 প্রোজেক্ট সেটআপ (Installation Guide)

আপনার লোকাল মেশিনে এটি রান করতে নিচের ধাপগুলো অনুসরণ করুন:

1. **রিপোজিটরি ক্লোন করুন:**
   ```bash
   git clone https://github.com/your-username/faruk-ai.git
   cd faruk-ai
ডিপেন্ডেন্সি ইন্সটল করুন:
 ```bash
composer install
npm install && npm run dev

Environment ফাইল সেটআপ করুন:
.env.example কে কপি করে .env তৈরি করুন এবং ডাটাবেস ও API Key গুলো বসান।

**Env**
 ```bash
DB_DATABASE=faruk_ai
DB_USERNAME=root
DB_PASSWORD=

GROQ_API_KEY=your_groq_api_key_here
TAVILY_API_KEY=your_tavily_api_key_here

**ডাটাবেস মাইগ্রেশন:**
 ```bash
php artisan migrate

**অ্যাপ্লিকেশন রান করুন:**
 ```bash
php artisan serve

📸 স্ক্রিনশট (Screenshots)
(এখানে আপনার প্রোজেক্টের কিছু স্ক্রিনশট দিতে পারেন)
Desktop View	Mobile View
<img src="https://via.placeholder.com/600x400" width="400">	<img src="https://via.placeholder.com/200x400" width="150">

**🛑 প্রয়োজনীয় API Keys**
এই প্রোজেক্টটি চালানোর জন্য আপনার দুটি ফ্রি API Key প্রয়োজন হবে:
Groq Console থেকে AI Key সংগ্রহ করুন।
Tavily AI থেকে Search API Key সংগ্রহ করুন।

🤝 কন্ট্রিবিউশন
আপনি যদি এই প্রোজেক্টে অবদান রাখতে চান, তবে একটি Pull Request পাঠান অথবা Issue ক্রিয়েট করুন।
