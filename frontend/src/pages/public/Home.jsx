import HeroSlider from '../../components/public/HeroSlider'
import StatsBar from '../../components/public/StatsBar'
import NewsSection from '../../components/public/NewsSection'
import WorkAreas from '../../components/public/WorkAreas'
import MediaSection from '../../components/public/MediaSection'
import PartnersScroll from '../../components/public/PartnersScroll'

export default function Home() {
  return (
    <>
      <HeroSlider />
      <StatsBar />
      <NewsSection />
      <WorkAreas />
      <MediaSection />
      <PartnersScroll />
    </>
  )
}
