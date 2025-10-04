# 🐍 Python Alternative - Medical Chatbot

If you want to completely avoid Laravel/PHP and MySQL, here's a **simple Python alternative**:

## 🚀 Quick Python Setup

### **Option 1: Flask + SQLite (Simplest)**
```python
# app.py
from flask import Flask, render_template, request, jsonify
import sqlite3
import requests
import os

app = Flask(__name__)

# Initialize SQLite database
def init_db():
    conn = sqlite3.connect('medical_chat.db')
    conn.execute('''
        CREATE TABLE IF NOT EXISTS messages (
            id INTEGER PRIMARY KEY,
            user_message TEXT,
            ai_response TEXT,
            timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ''')
    conn.close()

@app.route('/')
def index():
    return render_template('chat.html')

@app.route('/chat', methods=['POST'])
def chat():
    user_message = request.json['message']
    
    # Simple fallback responses (no API needed)
    if 'headache' in user_message.lower():
        ai_response = "For headaches, try rest, hydration, and over-the-counter pain relief. Consult a doctor if severe."
    elif 'fever' in user_message.lower():
        ai_response = "For fever, rest, stay hydrated, and monitor temperature. Seek medical help if over 103°F."
    else:
        ai_response = "I'm here to help with medical questions. Please describe your symptoms."
    
    # Save to database
    conn = sqlite3.connect('medical_chat.db')
    conn.execute('INSERT INTO messages (user_message, ai_response) VALUES (?, ?)', 
                (user_message, ai_response))
    conn.commit()
    conn.close()
    
    return jsonify({'response': ai_response})

if __name__ == '__main__':
    init_db()
    app.run(debug=True)
```

### **Option 2: Streamlit (Even Simpler!)**
```python
# streamlit_app.py
import streamlit as st
import sqlite3
from datetime import datetime

st.title("🏥 Medical AI Chatbot")

# Initialize database
@st.cache_resource
def init_db():
    conn = sqlite3.connect('medical_chat.db', check_same_thread=False)
    conn.execute('''
        CREATE TABLE IF NOT EXISTS messages (
            id INTEGER PRIMARY KEY,
            message TEXT,
            response TEXT,
            timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ''')
    conn.commit()
    return conn

conn = init_db()

# Chat interface
if "messages" not in st.session_state:
    st.session_state.messages = []

# Display chat history
for message in st.session_state.messages:
    with st.chat_message(message["role"]):
        st.markdown(message["content"])

# Chat input
if prompt := st.chat_input("Ask me about your health..."):
    # Add user message
    st.session_state.messages.append({"role": "user", "content": prompt})
    with st.chat_message("user"):
        st.markdown(prompt)
    
    # Generate response
    if 'headache' in prompt.lower():
        response = "For headaches: Rest in a dark room, stay hydrated, try cold compress. See a doctor if severe or persistent."
    elif 'fever' in prompt.lower():
        response = "For fever: Rest, drink fluids, monitor temperature. Seek medical help if over 103°F or with severe symptoms."
    else:
        response = "I can help with medical questions. Please describe your symptoms in detail."
    
    # Add AI response
    st.session_state.messages.append({"role": "assistant", "content": response})
    with st.chat_message("assistant"):
        st.markdown(response)
    
    # Save to database
    conn.execute('INSERT INTO messages (message, response) VALUES (?, ?)', (prompt, response))
    conn.commit()
```

## 🎯 **Recommended: Use the SQLite Laravel Fix**

**Run this command:**
```bash
setup-sqlite.bat
```

**Or manually update your .env file:**
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Then run:
```bash
php artisan migrate:fresh --force
php artisan serve
```

## ✅ **Why SQLite is Perfect:**
- **No server setup** - just works
- **No port conflicts** 
- **No MySQL headaches**
- **Perfect for development**
- **Easy to backup** (just copy the .sqlite file)

Would you like me to help you switch to SQLite right now?
