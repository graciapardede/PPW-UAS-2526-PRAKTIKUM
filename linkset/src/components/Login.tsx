import React, { useState, useEffect } from 'react'

export default function Login() {
  const [user, setUser] = useState<string | null>(null)
  const [input, setInput] = useState('')

  useEffect(() => {
    setUser(localStorage.getItem('linkset_user'))
  }, [])

  function handleLogin(e: any) {
    e.preventDefault()
    if (!input) return
    localStorage.setItem('linkset_user', input)
    setUser(input)
    setInput('')
    window.dispatchEvent(new Event('enrollment:changed'))
  }

  function logout() {
    localStorage.removeItem('linkset_user')
    setUser(null)
    window.dispatchEvent(new Event('enrollment:changed'))
  }

  if (user) {
    return (
      <div>
        <span>Hi, {user}</span>
        <button onClick={logout} style={{ marginLeft: 8 }}>Logout</button>
      </div>
    )
  }

  return (
    <form onSubmit={handleLogin} style={{ display: 'flex', gap: 8 }}>
      <input placeholder="username" value={input} onChange={(e) => setInput(e.target.value)} />
      <button type="submit">Login</button>
    </form>
  )
}
