#!/usr/bin/env node

const fs = require('fs')
const path = require('path')

// Get current date in YYYY.MM.DD format
const now = new Date()
const year = now.getFullYear()
const month = String(now.getMonth() + 1).padStart(2, '0')
const day = String(now.getDate()).padStart(2, '0')
const dateString = `${year}.${month}.${day}`

// Path to style.css
const styleCssPath = path.join(__dirname, '..', 'style.css')

// Read the file
let content = fs.readFileSync(styleCssPath, 'utf8')

// Replace the Version line with the new date
content = content.replace(/Version: \d{4}\.\d{2}\.\d{2}/, `Version: ${dateString}`)

// Write the file back
fs.writeFileSync(styleCssPath, content, 'utf8')

console.log(`Updated style.css Version to ${dateString}`)
