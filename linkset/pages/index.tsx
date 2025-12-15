import React from 'react'
import CourseList from '../src/components/CourseList'
import EnrollmentForm from '../src/components/EnrollmentForm'
import Login from '../src/components/Login'
import MyCourses from '../src/components/MyCourses'

export default function Home() {
  return (
    <div className="container">
      <header style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
        <h1>12S3101 — Pemrograman dan Pengujian Aplikasi Web</h1>
        <Login />
      </header>

      <p>Halaman 4 — Sistem pendaftaran kursus online sederhana. Pilih kursus dan daftar.</p>

      <section style={{ marginTop: 20 }}>
        <h2>Daftar Kursus</h2>
        <CourseList />
      </section>

      <section style={{ marginTop: 20 }}>
        <h2>Form Pendaftaran</h2>
        <EnrollmentForm />
      </section>

      <section style={{ marginTop: 20 }}>
        <h2>My Courses</h2>
        <MyCourses />
      </section>
    </div>
  )
}
