            <?php
            global $udft;
                udft::finish_content_layout();
            ?>

                </div> <!-- content-body-wrap -->

            </div> <!-- content-wrap -->


			<footer class="footer" role="contentinfo">

                <div class="footer-box container">
                    <div class="footer-content-row row">
                        <div class="footer-logo col-lg-12">
                            <a href="<?php echo site_url(); ?>"><img src="<?php echo $udft['header-logo']['url']; ?>"></a>
                        </div>
                    </div>
                </div>

			</footer>

            </div> <!-- site-wrapper -->

		</div> <!-- wrapper -->

		<?php wp_footer(); ?>

	</body>
</html>
