<template>
  <footer>
    <div class="container grid">
      <!-- Column 1 -->
      <div>
        <h3>Company</h3>
        <ul>
          <li><router-link to="#">About Us</router-link></li>
          <li><router-link to="#">Careers</router-link></li>
          <li><router-link to="#">Press</router-link></li>
        </ul>
      </div>

      <!-- Column 2 -->
      <div>
        <h3>Support</h3>
        <ul>
          <li><router-link to="#">Help Center</router-link></li>
          <li><router-link to="#">FAQ</router-link></li>
          <li><router-link to="#">Contact Us</router-link></li>
        </ul>
      </div>

      <!-- Column 3 -->
      <div>
        <h3>Legal</h3>
        <ul>
          <li><router-link to="#">Privacy Policy</router-link></li>
          <li><router-link to="#">Terms of Service</router-link></li>
          <li><router-link to="#">Cookie Policy</router-link></li>
        </ul>
      </div>

      <!-- Column 4 -->
      <div>
        <h3>Follow Us</h3>
        <div class="flex space-x-4">
          <router-link to="#">Facebook</router-link>
          <router-link to="#">Twitter</router-link>
          <router-link to="#">LinkedIn</router-link>
        </div>
      </div>
    </div>
    <div class="copyright-text">
      &copy; {{ new Date().getFullYear() }} ATI Limited. All Rights Reserved.
    </div>
  </footer>
</template>

<style scoped>
footer {
  margin-left: 16vw;
  position: relative;
  padding-top: 2.5rem;
  padding-bottom: 2.5rem;
}

h3 {
  font-size: 1.125rem;
  font-weight: 600;
  margin-bottom: 1rem;
}

ul {
  list-style: none;
  padding: 0;
}

ul li {
  margin-bottom: 0.5rem;
}

a {
  color: var(--text, white);
  text-decoration: none;
  transition: color 0.3s;
}

a:hover {
  color: var(--primary, pink);
}

.container {
  margin-left: 5vw;
  margin-right: auto;
  padding-left: 1.5rem;
  padding-right: 1.5rem;
}

.grid {
  display: grid;
  grid-template-columns: repeat(1, 1fr);
  gap: 2rem;
}

.flex {
  display: flex;
}

.space-x-4 a:not(:last-child) {
  margin-right: 1rem;
}

.copyright-text {
  text-align: center;
  font-size: 0.875rem;
  margin-top: 2rem;
}

@media (min-width: 768px) {
  .grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (min-width: 1024px) {
  .grid {
    grid-template-columns: repeat(4, 1fr);
  }
}
</style>
