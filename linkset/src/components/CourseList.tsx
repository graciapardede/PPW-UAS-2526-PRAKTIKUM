import React from 'react'
import CourseCard from './CourseCard'

const COURSES = [
  { id: 'C101', name: 'Web Programming Basics', duration: '4 weeks', price: 'Free' },
  { id: 'C102', name: 'Frontend with React', duration: '6 weeks', price: '$50' },
  { id: 'C103', name: 'Backend with Node.js', duration: '6 weeks', price: '$60' },
  { id: 'C104', name: 'Testing Web Apps', duration: '3 weeks', price: '$40' },
]

export default function CourseList() {
  return (
    <div className="grid">
      {COURSES.map((c) => (
        <CourseCard key={c.id} id={c.id} name={c.name} duration={c.duration} price={c.price} />
      ))}
    </div>
  )
}
