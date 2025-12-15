import React, { useEffect, useState } from 'react'

function loadEnrollments() {
  return JSON.parse(localStorage.getItem('linkset_enrollments') || '[]')
}

export default function MyCourses() {
  const [enrollments, setEnrollments] = useState<any[]>([])
  const [user, setUser] = useState<string | null>(null)

  useEffect(() => {
    setUser(localStorage.getItem('linkset_user'))
    setEnrollments(loadEnrollments())

    function onChange() { setEnrollments(loadEnrollments()); setUser(localStorage.getItem('linkset_user')) }
    window.addEventListener('enrollment:changed', onChange)
    return () => window.removeEventListener('enrollment:changed', onChange)
  }, [])

  if (!user) return <p>Please login to see your courses.</p>

  const mine = enrollments.filter((e) => e.name === user || e.email === user || e.email === `${user}@local`)

  if (!mine.length) return <p>You have not enrolled in any courses yet.</p>

  return (
    <ul>
      {mine.map((m) => (
        <li key={m.id}>{m.course} — {m.name} ({new Date(m.createdAt).toLocaleString()})</li>
      ))}
    </ul>
  )
}
