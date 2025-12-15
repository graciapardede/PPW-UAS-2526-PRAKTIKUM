import React, { useState } from 'react'

const COURSES = [
  { id: 'C101', name: 'Web Programming Basics' },
  { id: 'C102', name: 'Frontend with React' },
  { id: 'C103', name: 'Backend with Node.js' },
  { id: 'C104', name: 'Testing Web Apps' },
]

function saveEnrollment(data: any) {
  const key = 'linkset_enrollments'
  const existing = JSON.parse(localStorage.getItem(key) || '[]')
  existing.push(data)
  localStorage.setItem(key, JSON.stringify(existing))
}

export default function EnrollmentForm() {
  const [name, setName] = useState('')
  const [email, setEmail] = useState('')
  const [course, setCourse] = useState(COURSES[0].id)
  const [message, setMessage] = useState('')

  function handleSubmit(e: React.FormEvent) {
    e.preventDefault()
    if (!name || !email) {
      setMessage('Name and email are required')
      return
    }
    const record = { id: Date.now(), name, email, course, createdAt: new Date().toISOString() }
    saveEnrollment(record)
    setMessage('Enrollment saved locally')
    setName('')
    setEmail('')
    setCourse(COURSES[0].id)
    window.dispatchEvent(new Event('enrollment:changed'))
  }

  return (
    <form onSubmit={handleSubmit} style={{ maxWidth: 600 }}>
      <div>
        <label>Name</label>
        <input value={name} onChange={(e) => setName(e.target.value)} />
      </div>
      <div>
        <label>Email</label>
        <input value={email} onChange={(e) => setEmail(e.target.value)} />
      </div>
      <div>
        <label>Course</label>
        <select value={course} onChange={(e) => setCourse(e.target.value)}>
          {COURSES.map((c) => <option key={c.id} value={c.id}>{c.name}</option>)}
        </select>
      </div>
      <button type="submit">Enroll</button>
      {message && <p>{message}</p>}
    </form>
  )
}
