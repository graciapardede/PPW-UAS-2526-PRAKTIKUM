import { NextApiRequest, NextApiResponse } from 'next'
import prisma from '../../../lib/prisma'
import validUrl from 'valid-url'
import { getServerSession } from 'next-auth/next'
import authOptions from '../../auth/[...nextauth]'

// Handler to create a new short link
export default async function handler(req: NextApiRequest, res: NextApiResponse) {
  if (req.method !== 'POST') return res.status(405).json({ error: 'Method not allowed' })

  const { originalUrl, customSlug } = req.body

  // Try to resolve session (if any) to attach user
  let userId: number | undefined = undefined
  try {
    // getServerSession expects the NextAuth options; we import them from auth handler
    // @ts-ignore
    const session = await getServerSession(req as any, res as any, authOptions)
    if (session?.user?.id) userId = parseInt(session.user.id)
  } catch (e) {
    // session not available, proceed anonymously
  }

  // Validate original URL
  if (!originalUrl || !validUrl.isWebUri(originalUrl)) {
    return res.status(400).json({ error: 'Invalid originalUrl' })
  }

  // If customSlug provided, verify uniqueness
  if (customSlug) {
    const existing = await prisma.link.findUnique({ where: { customSlug } })
    if (existing) return res.status(409).json({ error: 'customSlug already in use' })
  }

  // Generate slug if not provided (simple random)
  let slug = customSlug
  if (!slug) {
    slug = Math.random().toString(36).slice(2, 9)
    // ensure unique
    const exists = await prisma.link.findUnique({ where: { customSlug: slug } })
    if (exists) slug = `${slug}-${Date.now().toString(36).slice(-4)}`
  }

  const link = await prisma.link.create({
    data: {
      originalUrl,
      customSlug: slug,
      userId: userId || undefined,
    },
  })

  res.status(201).json(link)
}
